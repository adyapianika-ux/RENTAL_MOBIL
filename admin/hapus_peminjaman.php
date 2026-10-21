<?php
include '../koneksi.php';

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM penyewaan WHERE id=$id");

header('Location: peminjaman.php');
exit;
?>