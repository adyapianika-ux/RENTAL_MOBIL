<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
mysqli_query($conn, "DELETE FROM pengembalian WHERE penyewaan_id=$id");

header("Location: pengembalian.php");
exit;
?>