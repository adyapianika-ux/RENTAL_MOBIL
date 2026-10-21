<?php
session_start();
include '../koneksi.php';

if ($_SESSION['role'] != 'admin') exit('Akses ditolak');

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM kategori WHERE id=$id");

header('Location: kategori.php');
exit;
?>