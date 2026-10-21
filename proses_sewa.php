<?php
session_start();
include 'koneksi.php';

if (($_SESSION['role'] ?? '') != 'pelanggan') {
    header("Location: login.php");
    exit;
}

$id = (int)$_POST['mobil'];
$user = (int)$_SESSION['id'];
$mulai = $_POST['tanggal_sewa'];
$akhir = $_POST['tanggal_kembali'];

if ($mulai < date('Y-m-d') || $akhir < $mulai) exit("Tanggal tidak valid.");

$q = mysqli_query($conn, "SELECT * FROM mobil WHERE id=$id AND LOWER(status)='tersedia'");
$m = mysqli_fetch_assoc($q);
if (!$m) exit("Mobil tidak tersedia.");

$hari = (strtotime($akhir) - strtotime($mulai)) / 86400 + 1;
$total = $hari * $m['harga_sewa'];
$kode = 'SR' . date('ymdHis') . rand(10, 99);

$sql = "INSERT INTO penyewaan
(kode_sewa,user_id,mobil_id,tanggal_sewa,tanggal_kembali,lama_sewa,total_harga,status)
VALUES ('$kode',$user,$id,'$mulai','$akhir',$hari,$total,'menunggu')";

if (mysqli_query($conn, $sql)) {
?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Sewa Berhasil</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    </head>

    <body class="bg-light">
        <div class="card shadow-sm mx-auto mt-5 p-4 text-center" style="max-width:450px">
            <h4 class="text-success">Sewa Berhasil!</h4>
            <p>Kode: <b><?= $kode ?></b></p>
            <h5>Rp<?= number_format($total, 0, ',', '.') ?></h5>
            <p><span class="badge bg-warning text-dark">Menunggu Persetujuan Admin</span></p>
            <div class="d-flex justify-content-center gap-2">
                <a href="index.php" class="btn btn-primary btn-sm">Beranda</a>
                <a href="pelanggan/riwayat.php" class="btn btn-outline-primary btn-sm">Riwayat</a>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>