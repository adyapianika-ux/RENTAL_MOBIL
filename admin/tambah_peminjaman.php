<?php
session_start();
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $u = (int)$_POST['user_id'];
    $m = (int)$_POST['mobil_id'];
    $a = $_POST['tanggal_sewa'];
    $b = $_POST['tanggal_kembali'];
    $lama = max(1, ceil((strtotime($b) - strtotime($a)) / 86400));
    $harga = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga_sewa FROM mobil WHERE id=$m"))['harga_sewa'];

    mysqli_query($conn, "INSERT INTO penyewaan
    (kode_sewa,user_id,mobil_id,tanggal_sewa,tanggal_kembali,lama_sewa,total_harga,status)
    VALUES ('SW" . time() . "',$u,$m,'$a','$b',$lama," . ($lama * $harga) . ",'menunggu')");

    header('Location: peminjaman.php');
}
?>
<form method="post">
    <select name="user_id" required>
        <?php
        $q = mysqli_query($conn, "SELECT id,nama FROM users WHERE role='pelanggan'");
        while ($d = mysqli_fetch_assoc($q)) echo "<option value='{$d['id']}'>{$d['nama']}</option>";
        ?>
    </select>
    <select name="mobil_id" required>
        <?php
        $q = mysqli_query($conn, "SELECT id,nama_mobil FROM mobil WHERE status='tersedia'");
        while ($d = mysqli_fetch_assoc($q)) echo "<option value='{$d['id']}'>{$d['nama_mobil']}</option>";
        ?>
    </select>
    <input type="date" name="tanggal_sewa" required>
    <input type="date" name="tanggal_kembali" required>
    <button name="simpan">Simpan</button>
</form>