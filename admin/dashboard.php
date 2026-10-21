<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$mobil = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM mobil"));
$kategori = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kategori"));
$peminjaman = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM penyewaan"));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin SiRentCar</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary px-3">
        <span class="navbar-brand">SiRentCar - Admin</span>
        <a href="../logout.php" class="btn btn-warning btn-sm">Logout</a>
    </nav>

    <div class="container mt-4">
        <h3>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?>!</h3>
        <p>Dashboard Admin SiRentCar</p>

        <div class="row">

            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Data Users</h5>
                    <h3><?= $users ?></h3>
                    <a href="users.php">Kelola Users</a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Data Mobil</h5>
                    <h3><?= $mobil ?></h3>
                    <a href="mobil.php">Kelola Mobil</a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Data Kategori</h5>
                    <h3><?= $kategori ?></h3>
                    <a href="kategori.php">Kelola Kategori</a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Data Peminjaman</h5>
                    <h3><?= $peminjaman ?></h3>
                    <a href="peminjaman.php">Kelola Peminjaman</a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Data Pengembalian</h5>
                    <a href="pengembalian.php">Kelola Pengembalian</a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Log Aktivitas</h5>
                    <a href="log_aktivitas.php">Lihat Aktivitas</a>
                </div>
            </div>

        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>