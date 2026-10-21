<?php
session_start();

if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    !isset($_SESSION['id']) ||
    ($_SESSION['role'] ?? '') !== 'pelanggan'
) {
    header("Location: ../login.php");
    exit;
}

include '../koneksi.php';

$id = (int) $_SESSION['id'];

$q = mysqli_query($conn, "SELECT p.*, m.nama_mobil, pg.denda
    FROM penyewaan p
    JOIN mobil m ON p.mobil_id = m.id
    LEFT JOIN pengembalian pg ON p.id = pg.penyewaan_id
    WHERE p.user_id = $id
    ORDER BY p.id DESC");

if (!$q) {
    die("Gagal mengambil riwayat penyewaan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Penyewaan</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3>Riwayat Penyewaan</h3>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Mobil</th>
                    <th>Tanggal Sewa</th>
                    <th>Total</th>
                    <th>Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($q) > 0) { ?>
                    <?php while ($d = mysqli_fetch_assoc($q)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($d['kode_sewa']) ?></td>
                            <td><?= htmlspecialchars($d['nama_mobil']) ?></td>
                            <td>
                                <?= htmlspecialchars($d['tanggal_sewa']) ?>
                                -
                                <?= htmlspecialchars($d['tanggal_kembali']) ?>
                            </td>
                            <td>Rp<?= number_format($d['total_harga'], 0, ',', '.') ?></td>
                            <td>Rp<?= number_format($d['denda'] ?? 0, 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($d['status']) ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada riwayat penyewaan.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="../index.php" class="btn btn-secondary">Kembali</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>

</body>

</html>