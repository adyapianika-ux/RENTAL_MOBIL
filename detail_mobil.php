<?php
include 'koneksi.php';

$id = (int)($_GET['id'] ?? 0);
$q = mysqli_query($conn, "SELECT * FROM mobil WHERE id=$id");
$m = mysqli_fetch_assoc($q);

if (!$m) exit("Mobil tidak ditemukan.");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Mobil - SiRentCar</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="index.php" class="navbar-brand">SiRentCar</a>
            <a href="index.php" class="btn btn-light btn-sm">Kembali</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card p-3 mx-auto" style="max-width:500px">
            <h3><?= htmlspecialchars($m['nama_mobil']) ?></h3>

            <?php if (!empty($m['foto'])): ?>
                <img src="uploads/<?= htmlspecialchars($m['foto']) ?>"
                    class="img-fluid mb-3"
                    style="height:250px;object-fit:cover">
            <?php endif; ?>

            <p>Merk: <?= htmlspecialchars($m['merk']) ?></p>
            <p>Plat: <?= htmlspecialchars($m['nomor_plat']) ?></p>
            <p>Tahun: <?= htmlspecialchars($m['tahun']) ?></p>
            <p>Harga: Rp <?= number_format($m['harga_sewa'], 0, ',', '.') ?>/hari</p>
            <p>Status: <?= htmlspecialchars($m['status']) ?></p>
            <p><?= htmlspecialchars($m['deskripsi'] ?? '-') ?></p>

            <?php if (strtolower($m['status']) == 'tersedia'): ?>
                <a href="login.php?mobil=<?= $m['id'] ?>"
                    class="btn btn-primary">Sewa Mobil</a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>
                    Mobil Tidak Tersedia
                </button>
            <?php endif; ?>

            <a href="index.php?kategori=<?= $m['kategori_id'] ?>" class="btn btn-outline-secondary mt-2">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>