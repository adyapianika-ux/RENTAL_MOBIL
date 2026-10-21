<?php
session_start();
include 'koneksi.php';

$kategori = mysqli_query($conn, "SELECT * FROM kategori");
$id = (int)($_GET['kategori'] ?? 0);

$mobil = null;
if ($id > 0) {
    $mobil = mysqli_query($conn, "SELECT * FROM mobil
        WHERE kategori_id=$id AND LOWER(status)='tersedia'");
}

if (isset($_SESSION['login']) && $_SESSION['login'] && ($_SESSION['role'] ?? '') == 'pelanggan') {
    $link_riwayat = 'pelanggan/riwayat.php';
} else {
    $link_riwayat = 'login.php';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiRentCar</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">SiRentCar</a>

            <div class="navbar-nav ms-auto flex-row align-items-center gap-3">
                <a href="index.php" class="nav-link">Beranda</a>
                <a href="#kategori" class="nav-link">Kategori</a>
                <a href="<?= $link_riwayat ?>" class="nav-link">Riwayat Sewa</a>
                <a href="login.php" class="btn btn-light btn-sm">Login</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Selamat Datang di SiRentCar</h3>
        <p>Silakan pilih kategori mobil yang ingin kamu sewa.</p>

        <h5 id="kategori">Kategori Mobil</h5>
        <form method="GET" class="mb-4">
            <select name="kategori" class="form-select"
                onchange="this.form.submit()">
                <option value="0">Pilih Kategori</option>
                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                    <option value="<?= $k['id'] ?>"
                        <?= $id == $k['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                <?php } ?>
            </select>
        </form>

        <?php if ($id > 0) { ?>
            <h5>Daftar Mobil</h5>
            <div class="row">
                <?php if ($mobil && mysqli_num_rows($mobil) > 0) {
                    while ($m = mysqli_fetch_assoc($mobil)) { ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <?php if (!empty($m['foto'])) { ?>
                                    <img src="uploads/<?= htmlspecialchars($m['foto']) ?>"
                                        class="card-img-top"
                                        style="height:200px;object-fit:cover"
                                        alt="Foto mobil">
                                <?php } ?>

                                <div class="card-body">
                                    <h5><?= htmlspecialchars($m['nama_mobil']) ?></h5>
                                    <p><?= htmlspecialchars($m['merk']) ?> - <?= $m['tahun'] ?></p>
                                    <p>Rp<?= number_format($m['harga_sewa'], 0, ',', '.') ?>/hari</p>

                                    <a href="detail_mobil.php?id=<?= $m['id'] ?>"
                                        class="btn btn-primary btn-sm">Detail Mobil</a>
                                </div>
                            </div>
                        </div>
                <?php }
                } else {
                    echo '<p>Belum ada mobil tersedia di kategori ini.</p>';
                } ?>
            </div>
        <?php } ?>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>