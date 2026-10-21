<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3>Kelola Kategori</h3>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
        <a href="tambah_kategori.php" class="btn btn-primary btn-sm">Tambah Kategori</a>

        <table class="table table-bordered mt-3 bg-white">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>

            <?php $no = 1;
            while ($k = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($k['nama_kategori']) ?></td>
                    <td>
                        <a href="edit_kategori.php?id=<?= $k['id'] ?>"
                            class="btn btn-warning btn-sm">Edit</a>

                        <a href="hapus_kategori.php?id=<?= $k['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus kategori ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>

</html>