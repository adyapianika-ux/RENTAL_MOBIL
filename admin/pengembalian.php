<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../login.php");
    exit;
}

$q = mysqli_query($conn, "SELECT pg.*, p.kode_sewa, p.tanggal_sewa,
    p.tanggal_kembali, u.nama, m.nama_mobil
    FROM pengembalian pg
    JOIN penyewaan p ON pg.penyewaan_id=p.id
    JOIN users u ON p.user_id=u.id
    JOIN mobil m ON p.mobil_id=m.id
    ORDER BY pg.tanggal_pengembalian DESC");
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">

<div class="container mt-4">
    <h3>Data Pengembalian</h3>
    <a href="tambah_pengembalian.php" class="btn btn-primary btn-sm">Tambah</a>

    <div class="table-responsive">
        <table class="table table-bordered mt-3">
            <tr>
                <th>Kode Sewa</th>
                <th>Nama Penyewa</th>
                <th>Mobil</th>
                <th>Tanggal Sewa</th>
                <th>Jadwal Kembali</th>
                <th>Tanggal Dikembalikan</th>
                <th>Kondisi</th>
                <th>Denda</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>

            <?php while ($d = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= htmlspecialchars($d['kode_sewa']) ?></td>
                    <td><?= htmlspecialchars($d['nama']) ?></td>
                    <td><?= htmlspecialchars($d['nama_mobil']) ?></td>
                    <td><?= htmlspecialchars($d['tanggal_sewa']) ?></td>
                    <td><?= htmlspecialchars($d['tanggal_kembali']) ?></td>
                    <td><?= htmlspecialchars($d['tanggal_pengembalian']) ?></td>
                    <td><?= htmlspecialchars($d['kondisi_mobil']) ?></td>
                    <td>Rp<?= number_format($d['denda'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($d['catatan'] ?? '-') ?></td>
                    <td>
                        <a href="edit_pengembalian.php?id=<?= $d['penyewaan_id'] ?>"
                            class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus_pengembalian.php?id=<?= $d['penyewaan_id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
</div>