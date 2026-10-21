<?php
session_start();
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $kategori = (int)$_POST['kategori'];
    $merk = $_POST['merk'];
    $plat = $_POST['plat'];
    $tahun = (int)$_POST['tahun'];
    $harga = (int)$_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    $foto = time() . '_' . basename($_FILES['foto']['name']);
    if (!is_dir('../uploads')) mkdir('../uploads');
    move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto);

    mysqli_query($conn, "INSERT INTO mobil
    (nama_mobil,kategori_id,merk,nomor_plat,tahun,harga_sewa,foto,deskripsi,status)
    VALUES ('$nama',$kategori,'$merk','$plat',$tahun,$harga,'$foto','$deskripsi','tersedia')");

    header("Location: mobil.php");
    exit;
}
$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<div class="container mt-4" style="max-width:500px">
    <h3>Tambah Mobil</h3>
    <form method="POST" enctype="multipart/form-data">
        <input name="nama" class="form-control mb-2" placeholder="Nama mobil" required>
        <select name="kategori" class="form-control mb-2" required>
            <option value="">Pilih kategori</option>
            <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
            <?php endwhile; ?>
        </select>
        <input name="merk" class="form-control mb-2" placeholder="Merk" required>
        <input name="plat" class="form-control mb-2" placeholder="Nomor plat" required>
        <input name="tahun" type="number" class="form-control mb-2" placeholder="Tahun" required>
        <input name="harga" type="number" class="form-control mb-2" placeholder="Harga sewa" required>
        <input name="foto" type="file" accept="image/*" class="form-control mb-2" required>
        <textarea name="deskripsi" class="form-control mb-2" placeholder="Deskripsi"></textarea>
        <button name="simpan" class="btn btn-primary">Simpan</button>
        <a href="mobil.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>