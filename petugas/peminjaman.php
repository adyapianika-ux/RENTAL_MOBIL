<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'petugas') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'], $_GET['aksi'])) {
    $id = (int)$_GET['id'];
    $aksi = $_GET['aksi'];

    $q = mysqli_query($conn, "SELECT mobil_id FROM penyewaan
        WHERE id=$id AND status='menunggu'");
    $d = mysqli_fetch_assoc($q);

    if ($d && $aksi == 'setujui') {
        $mobil = (int)$d['mobil_id'];
        mysqli_query($conn, "UPDATE mobil SET status='disewa'
            WHERE id=$mobil AND status='tersedia'");

        if (mysqli_affected_rows($conn) > 0) {
            mysqli_query($conn, "UPDATE penyewaan
                SET status='disetujui' WHERE id=$id");
        }
    } elseif ($d && $aksi == 'tolak') {
        mysqli_query($conn, "UPDATE penyewaan
            SET status='ditolak' WHERE id=$id");
    }

    header("Location: peminjaman.php");
    exit;
}

$q = mysqli_query($conn, "SELECT p.*, u.nama, u.no_hp
    FROM penyewaan p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC");
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">

<div class="container mt-4">
    <h3>Persetujuan Peminjaman</h3>

    <table class="table table-bordered">
        <tr>
            <th>Kode</th>
            <th>Nama Penyewa</th>
            <th>No. HP</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php while ($d = mysqli_fetch_assoc($q)) { ?>
            <tr>
                <td><?= htmlspecialchars($d['kode_sewa']) ?></td>
                <td><?= htmlspecialchars($d['nama']) ?></td>
                <td><?= htmlspecialchars($d['no_hp']) ?></td>
                <td>
                    <?= htmlspecialchars($d['tanggal_sewa']) ?>
                    -
                    <?= htmlspecialchars($d['tanggal_kembali']) ?>
                </td>
                <td>Rp<?= number_format($d['total_harga'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($d['status']) ?></td>
                <td>
                    <?php if (!empty($d['no_hp'])) {
                        $hp = preg_replace('/\D/', '', $d['no_hp']);
                        if (substr($hp, 0, 1) == '0') {
                            $hp = '62' . substr($hp, 1);
                        }
                    ?>
                        <a href="https://wa.me/<?= $hp ?>"
                            target="_blank" class="btn btn-success btn-sm">WhatsApp</a>
                    <?php } ?>

                    <?php if ($d['status'] == 'menunggu') { ?>
                        <a href="?id=<?= $d['id'] ?>&aksi=setujui"
                            class="btn btn-primary btn-sm">Setujui</a>
                        <a href="?id=<?= $d['id'] ?>&aksi=tolak"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Tolak peminjaman ini?')">Tolak</a>
                    <?php } else { ?>
                        <span class="badge bg-secondary">
                            <?= htmlspecialchars(ucfirst($d['status'])) ?>
                        </span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
</div>