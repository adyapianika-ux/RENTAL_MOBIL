<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

$cek = mysqli_query($conn, "SELECT id FROM penyewaan WHERE user_id=$id");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
        alert('User tidak bisa dihapus karena memiliki riwayat penyewaan!');
        location.href='users.php';
    </script>";
    exit;
}

mysqli_query($conn, "DELETE FROM users WHERE id=$id");

header("Location: users.php");
exit;
