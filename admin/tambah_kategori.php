<?php
session_start();
include '../koneksi.php';

if ($_SESSION['role'] != 'admin') exit('Akses ditolak');

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    header("Location: kategori.php");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">

<div class="container mt-4">
    <h3>Tambah Kategori</h3>
    <form method="POST">
        <input name="nama_kategori" class="form-control mb-3"
            placeholder="Nama kategori" required>
        <button name="simpan" class="btn btn-primary">Simpan</button>
        <a href="kategori.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>