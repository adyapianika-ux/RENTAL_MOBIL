<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
mysqli_query($conn, "DELETE FROM mobil WHERE id=$id");

header("Location: mobil.php");
exit;
?>