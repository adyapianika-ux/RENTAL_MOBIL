<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'petugas') {
    header("Location: ../login.php");
    exit;
}

$mulai = $_GET['mulai'] ?? '';
$akhir = $_GET['akhir'] ?? '';

$where = "";
if ($mulai && $akhir) {
    $where = "WHERE DATE(p.tanggal_sewa) BETWEEN '$mulai' AND '$akhir'";
}

$q = mysqli_query($conn, "SELECT p.*, m.nama_mobil, u.nama,
    pg.denda FROM penyewaan p
    JOIN mobil m ON p.mobil_id=m.id
    JOIN users u ON p.user_id=u.id
    LEFT JOIN pengembalian pg ON p.id=pg.penyewaan_id
    $where
    ORDER BY p.id DESC");

$total = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(total_harga) total FROM penyewaan
    WHERE status IN ('disewa','selesai')"
));

$denda = mysqli_fetch_assoc(mysqli_query(
    $conn,
    "SELECT SUM(denda) total FROM pengembalian"
));
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">

<div class="container mt-4">
    <h3>Laporan Penyewaan Mobil</h3>

    <form method="GET" class="mb-3">
        <input type="date" name="mulai"
            value="<?= htmlspecialchars($mulai) ?>" required>
        <input type="date" name="akhir"
            value="<?= htmlspecialchars($akhir) ?>" required>
        <button class="btn btn-primary btn-sm">Filter</button>
        <a href="laporan.php" class="btn btn-secondary btn-sm">Reset</a>
    </form>

    <p>Pendapatan: Rp<?= number_format($total['total'] ?? 0, 0, ',', '.') ?>
        | Denda: Rp<?= number_format($denda['total'] ?? 0, 0, ',', '.') ?></p>

    <button onclick="window.print()" class="btn btn-warning btn-sm mb-3">Cetak Laporan</button>

    <table class="table table-bordered">
        <tr>
            <th>Kode</th>
            <th>Penyewa</th>
            <th>Tanggal Sewa</th>
            <th>Tanggal Pengembalian</th>
            <th>Mobil</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php while ($d = mysqli_fetch_assoc($q)) { ?>
            <tr>
                <td><?= htmlspecialchars($d['kode_sewa']) ?></td>
                <td><?= htmlspecialchars($d['nama']) ?></td>
                <td><?= htmlspecialchars($d['tanggal_sewa']) ?></td>
                <td><?= htmlspecialchars($d['tanggal_kembali']) ?></td>
                <td><?= htmlspecialchars($d['nama_mobil']) ?></td>
                <td>Rp<?= number_format($d['total_harga'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($d['status']) ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="cetak(this)">Cetak Struk</button>
                    <span hidden>
                        <h3>SiRentCar</h3>
                        <h4>Struk Penyewaan</h4>
                        Kode: <?= htmlspecialchars($d['kode_sewa']) ?><br>
                        Penyewa: <?= htmlspecialchars($d['nama']) ?><br>
                        Mobil: <?= htmlspecialchars($d['nama_mobil']) ?><br>
                        Tanggal: <?= htmlspecialchars($d['tanggal_sewa']) ?> - <?= htmlspecialchars($d['tanggal_kembali']) ?><br>
                        Total: Rp<?= number_format($d['total_harga'], 0, ',', '.') ?><br>
                        Denda: Rp<?= number_format($d['denda'] ?? 0, 0, ',', '.') ?><br>
                        Status: <?= htmlspecialchars($d['status']) ?>
                    </span>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
</div>

<script>
    function cetak(b) {
        let w = window.open('', '', 'width=400,height=500');
        w.document.write('<body style="font-family:Arial;padding:20px">' + b.nextElementSibling.innerHTML + '</body>');
        w.document.close();
        w.print();
    }
</script>