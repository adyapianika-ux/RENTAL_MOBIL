<?php
include '../koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$q = mysqli_query($conn, "SELECT * FROM mobil WHERE id=$id");
$m = mysqli_fetch_assoc($q);
if (!$m) exit("Mobil tidak ditemukan.");

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $merk = $_POST['merk'];
    $plat = $_POST['plat'];
    $tahun = (int)$_POST['tahun'];
    $harga = (int)$_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $foto = $m['foto'];

    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . '_' . basename($_FILES['foto']['name']);
        if (!is_dir('../uploads')) mkdir('../uploads');
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto);
    }

    mysqli_query($conn, "UPDATE mobil SET nama_mobil='$nama',
    merk='$merk', nomor_plat='$plat', tahun=$tahun,
    harga_sewa=$harga, foto='$foto', deskripsi='$deskripsi'
    WHERE id=$id");

    header("Location: mobil.php");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<div class="container mt-4" style="max-width:500px">
    <h3>Edit Mobil</h3>
    <form method="POST" enctype="multipart/form-data">
        <input name="nama" class="form-control mb-2" value="<?= htmlspecialchars($m['nama_mobil']) ?>" required>
        <input name="merk" class="form-control mb-2" value="<?= htmlspecialchars($m['merk']) ?>" required>
        <input name="plat" class="form-control mb-2" value="<?= htmlspecialchars($m['nomor_plat']) ?>" required>
        <input name="tahun" type="number" class="form-control mb-2" value="<?= $m['tahun'] ?>" required>
        <input name="harga" type="number" class="form-control mb-2" value="<?= $m['harga_sewa'] ?>" required>

        <p>Foto sekarang:</p>
        <?php if ($m['foto']): ?>
            <img src="../uploads/<?= htmlspecialchars($m['foto']) ?>" width="120" class="mb-2">
        <?php endif; ?>
        <input type="file" name="foto" accept="image/*" class="form-control mb-2">

        <textarea name="deskripsi" class="form-control mb-2"><?= htmlspecialchars($m['deskripsi'] ?? '') ?></textarea>
        <button name="simpan" class="btn btn-primary">Simpan</button>
        <a href="mobil.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>