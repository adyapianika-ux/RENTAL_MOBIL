<?php
session_start();
if (($_SESSION['role'] ?? '') != 'petugas') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Petugas</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand">SiRentCar - Petugas</span>
            <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Dashboard Petugas</h3>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['nama'] ?? 'Petugas') ?>!</p>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Persetujuan Peminjaman</h5>
                    <p>Menyetujui atau menolak pengajuan sewa.</p>
                    <a href="peminjaman.php" class="btn btn-primary">Buka</a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Pengembalian</h5>
                    <p>Memantau pengembalian dan kondisi mobil.</p>
                    <a href="pengembalian.php" class="btn btn-primary">Buka</a>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>Laporan</h5>
                    <p>Melihat dan mencetak laporan penyewaan.</p>
                    <a href="laporan.php" class="btn btn-primary">Buka</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>