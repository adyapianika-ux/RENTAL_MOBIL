<?php
session_start();
include '../koneksi.php';

if ($_SESSION['role'] != 'admin') exit('Akses ditolak');

$id = (int)$_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM kategori WHERE id=$id");
$k = mysqli_fetch_assoc($q);

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama' WHERE id=$id");
    header('Location: kategori.php');
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">

<div class="container mt-4">
    <h3>Edit Kategori</h3>
    <form method="post">
        <input name="nama_kategori" class="form-control mb-3"
            value="<?= htmlspecialchars($k['nama_kategori']) ?>" required>
        <button name="simpan" class="btn btn-primary">Simpan</button>
        <a href="kategori.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>