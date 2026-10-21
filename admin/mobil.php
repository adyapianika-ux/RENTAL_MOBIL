<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$q = mysqli_query($conn, "SELECT mobil.*, kategori.nama_kategori
FROM mobil JOIN kategori ON mobil.kategori_id = kategori.id");
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">

<div class="container mt-4">
    <h3>Kelola Mobil</h3>

    <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
    <a href="tambah_mobil.php" class="btn btn-primary btn-sm">Tambah Mobil</a>

    <table class="table table-bordered mt-3">
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Mobil</th>
            <th>Kategori</th>
            <th>Harga/Hari</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php $no = 1; while ($m = mysqli_fetch_assoc($q)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td>
                <?php if (!empty($m['foto'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($m['foto']) ?>"
                         width="80" alt="Foto mobil">
                <?php else: ?>
                    Belum ada foto
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($m['nama_mobil']) ?></td>
            <td><?= htmlspecialchars($m['nama_kategori']) ?></td>
            <td>Rp <?= number_format($m['harga_sewa'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($m['status']) ?></td>
            <td>
                <a href="edit_mobil.php?id=<?= $m['id'] ?>"
                   class="btn btn-warning btn-sm">Edit</a>

                <a href="hapus_mobil.php?id=<?= $m['id'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Yakin hapus mobil ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>