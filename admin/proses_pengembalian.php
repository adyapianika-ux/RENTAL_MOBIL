<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) $_POST['penyewaan_id'];
$tanggal = $_POST['tanggal_pengembalian'];
$kondisi = $_POST['kondisi_mobil'];
$denda = (int) $_POST['denda'];
$catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

if ($denda < 0) exit("Denda tidak valid.");

$q = mysqli_query($conn, "SELECT mobil_id FROM penyewaan
WHERE id=$id AND status IN ('disetujui','disewa')");

$data = mysqli_fetch_assoc($q);

if (!$data) exit("Data penyewaan tidak ditemukan atau sudah selesai.");

$tanggal = mysqli_real_escape_string($conn, $tanggal);
$kondisi = mysqli_real_escape_string($conn, $kondisi);

$sql = "INSERT INTO pengembalian
(penyewaan_id, tanggal_pengembalian, kondisi_mobil, denda, catatan)
VALUES ($id, '$tanggal', '$kondisi', $denda, '$catatan')";

if (mysqli_query($conn, $sql)) {
    mysqli_query($conn, "UPDATE penyewaan SET status='selesai' WHERE id=$id");
    mysqli_query($conn, "UPDATE mobil SET status='tersedia' WHERE id=" . (int)$data['mobil_id']);

    header("Location: pengembalian.php");
    exit;
}

echo "Gagal menyimpan pengembalian: " . mysqli_error($conn);
