<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $id = (int) $_POST['id'];
    $tanggal = $_POST['tanggal'];
    $kondisi = $_POST['kondisi'];
    $denda = max(0, (int) $_POST['denda']);

    $q = mysqli_query($conn, "SELECT mobil_id FROM penyewaan
        WHERE id=$id AND status IN ('disetujui','disewa')");
    $data = mysqli_fetch_assoc($q);

    if ($data) {
        $tanggal = mysqli_real_escape_string($conn, $tanggal);
        $kondisi = mysqli_real_escape_string($conn, $kondisi);

        $simpan = mysqli_query($conn, "INSERT INTO pengembalian
            (penyewaan_id, tanggal_pengembalian, kondisi_mobil, denda, catatan)
            VALUES ($id, '$tanggal', '$kondisi', $denda, '')");

        if ($simpan) {
            mysqli_query($conn, "UPDATE penyewaan SET status='selesai' WHERE id=$id");
            mysqli_query($conn, "UPDATE mobil SET status='tersedia' WHERE id=" . (int)$data['mobil_id']);
            header("Location: pengembalian.php");
            exit;
        } else {
            echo "Gagal menyimpan pengembalian.";
        }
    } else {
        echo "Data sewa tidak ditemukan atau sudah selesai.";
    }
}
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">

<div class="container mt-4" style="max-width:450px">
    <h3>Tambah Pengembalian</h3>

    <form method="post">
        <label>ID Sewa</label>
        <select name="id" class="form-control mb-2" required>
            <option value="">Pilih penyewaan</option>
            <?php
            $q = mysqli_query($conn, "SELECT id, kode_sewa FROM penyewaan
                WHERE status IN ('disetujui','disewa')");
            while ($d = mysqli_fetch_assoc($q)) {
                echo "<option value='{$d['id']}'>" .
                    htmlspecialchars($d['kode_sewa']) . "</option>";
            }
            ?>
        </select>

        <label>Tanggal Pengembalian</label>
        <input type="date" name="tanggal" class="form-control mb-2" required>

        <label>Kondisi Mobil</label>
        <select name="kondisi" class="form-control mb-2" required>
            <option value="baik">Baik</option>
            <option value="rusak">Rusak</option>
        </select>

        <label>Denda</label>
        <input type="number" name="denda" class="form-control mb-3"
            min="0" value="0" required>

        <button name="simpan" class="btn btn-primary btn-sm">Simpan</button>
        <a href="pengembalian.php" class="btn btn-secondary btn-sm">Kembali</a>
    </form>
</div>