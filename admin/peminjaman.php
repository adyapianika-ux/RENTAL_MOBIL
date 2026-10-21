<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../login.php");
    exit;
}

$q = mysqli_query($conn, "SELECT p.*, u.nama, m.nama_mobil
    FROM penyewaan p
    JOIN users u ON p.user_id=u.id
    JOIN mobil m ON p.mobil_id=m.id
    ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h3>Data Peminjaman</h3>
        <a href="tambah_peminjaman.php" class="btn btn-primary btn-sm">Tambah</a>

        <table class="table table-bordered table-striped mt-3">
            <tr>
                <th>Kode Sewa</th>
                <th>Nama Penyewa</th>
                <th>Mobil</th>
                <th>Tanggal Sewa</th>
                <th>Tanggal Kembali</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php while ($d = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= htmlspecialchars($d['kode_sewa']) ?></td>
                    <td><?= htmlspecialchars($d['nama']) ?></td>
                    <td><?= htmlspecialchars($d['nama_mobil']) ?></td>
                    <td><?= htmlspecialchars($d['tanggal_sewa']) ?></td>
                    <td><?= htmlspecialchars($d['tanggal_kembali']) ?></td>
                    <td>Rp<?= number_format($d['total_harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($d['status']) ?></td>
                    <td>
                        <a href="edit_peminjaman.php?id=<?= $d['id'] ?>"
                            class="btn btn-warning btn-sm">Edit</a>

                        <a href="hapus_peminjaman.php?id=<?= $d['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus peminjaman ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
</body>

</html>