<?php
session_start();
include 'koneksi.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];
$mobil = (int)($_POST['mobil'] ?? 0);

$q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$d = mysqli_fetch_assoc($q);

if ($d && password_verify($password, $d['password'])) {
    session_regenerate_id(true);
    $_SESSION['login'] = true;
    $_SESSION['id'] = $d['id'];
    $_SESSION['nama'] = $d['nama'];
    $_SESSION['role'] = $d['role'];

    if ($d['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($d['role'] == 'petugas') {
        header("Location: petugas/dashboard.php");
    } elseif ($d['role'] == 'pelanggan') {
        if ($mobil > 0) {
            header("Location: pelanggan/sewa.php?mobil=$mobil");
        } else {
            header("Location: index.php");
        }
    } else {
        session_unset();
        session_destroy();
        header("Location: login.php");
    }
    exit;
}

echo "<script>alert('Username atau password salah!');history.back();</script>";
