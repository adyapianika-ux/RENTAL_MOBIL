<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['mobil'] ?? $_GET['id'] ?? 0);

if ($id <= 0) {
    exit("Pilih mobil dari halaman utama terlebih dahulu.");
}

$query = mysqli_query($conn, "SELECT * FROM mobil WHERE id = $id");
$mobil = mysqli_fetch_assoc($query);

if (!$mobil) {
    exit("Mobil tidak ditemukan.");
}

if ($mobil['status'] != 'tersedia') {
    exit("Mobil sedang tidak tersedia.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sewa Mobil</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width:450px">
        <div class="card p-4">
            <h3>Sewa Mobil</h3>
            <p><?= htmlspecialchars($mobil['nama_mobil']) ?></p>
            <p>Harga: Rp <?= number_format($mobil['harga_sewa'], 0, ',', '.') ?>/hari</p>

            <form action="../proses_sewa.php" method="POST">
                <input type="hidden" name="mobil" value="<?= $mobil['id'] ?>">

                <label>Tanggal Sewa</label>
                <input type="date" name="tanggal_sewa"
                    min="<?= date('Y-m-d') ?>" class="form-control mb-3" required>

                <label>Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali"
                    min="<?= date('Y-m-d') ?>" class="form-control mb-3" required>

                <button type="submit" class="btn btn-primary w-100">
                    Ajukan Sewa
                </button>
            </form>

            <a href="../index.php" class="mt-3">Kembali ke Beranda</a>
        </div>
    </div>
</body>

</html>