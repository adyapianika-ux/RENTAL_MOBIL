<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if (isset($_POST['simpan'])) {
    $tgl = $_POST['tanggal'];
    $kondisi = $_POST['kondisi'];
    $denda = max(0, (int)$_POST['denda']);

    mysqli_query($conn, "UPDATE pengembalian SET
        tanggal_pengembalian='$tgl',
        kondisi_mobil='$kondisi', denda=$denda
        WHERE penyewaan_id=$id");

    header("Location: pengembalian.php");
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM pengembalian WHERE penyewaan_id=$id");
$d = mysqli_fetch_assoc($q);

if (!$d) {
    header("Location: pengembalian.php");
    exit;
}
?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<div class="container mt-4" style="max-width:400px">
    <h3>Edit Pengembalian</h3>
    <form method="post">
        <label>Tanggal</label>
        <input type="date" name="tanggal" value="<?= $d['tanggal_pengembalian'] ?>" class="form-control mb-2" required>
        <label>Kondisi</label>
        <select name="kondisi" class="form-control mb-2">
            <option value="baik" <?= strtolower($d['kondisi_mobil']) == 'baik' ? 'selected' : '' ?>>Baik</option>
            <option value="rusak" <?= strtolower($d['kondisi_mobil']) == 'rusak' ? 'selected' : '' ?>>Rusak</option>
        </select>
        <label>Denda</label>
        <input type="number" name="denda" value="<?= $d['denda'] ?>" min="0" class="form-control mb-3" required>
        <button name="simpan" class="btn btn-primary btn-sm">Simpan</button>
        <a href="pengembalian.php" class="btn btn-secondary btn-sm">Kembali</a>
    </form>
</div>