<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'petugas') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $tgl = mysqli_real_escape_string($conn, $_POST['tgl']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

    $q = mysqli_query($conn, "SELECT mobil_id,tanggal_kembali FROM penyewaan
        WHERE id=$id AND status IN ('disetujui','disewa')");
    $d = mysqli_fetch_assoc($q);

    if ($d) {
        $telat = max(0, (strtotime($tgl) - strtotime($d['tanggal_kembali'])) / 86400);
        $denda = (int)$telat * 50000;

        mysqli_query($conn, "INSERT INTO pengembalian
            (penyewaan_id,tanggal_pengembalian,kondisi_mobil,denda,catatan)
            VALUES ($id,'$tgl','baik',$denda,'$catatan')");

        mysqli_query($conn, "UPDATE penyewaan SET status='selesai' WHERE id=$id");
        mysqli_query($conn, "UPDATE mobil SET status='tersedia' WHERE id=" . (int)$d['mobil_id']);
    }

    header("Location: pengembalian.php");
    exit;
}

$q = mysqli_query($conn, "SELECT p.*,m.nama_mobil,u.nama,
    pg.tanggal_pengembalian,pg.denda,pg.catatan
    FROM penyewaan p
    JOIN mobil m ON p.mobil_id=m.id
    JOIN users u ON p.user_id=u.id
    LEFT JOIN pengembalian pg ON p.id=pg.penyewaan_id
    WHERE p.status IN ('disetujui','disewa','selesai')
    ORDER BY p.id DESC");
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">

<div class="container mt-4">
    <h3>Pengembalian Mobil</h3>

    <table class="table table-bordered">
        <tr>
            <th>Kode</th>
            <th>Penyewa</th>
            <th>Mobil</th>
            <th>Tanggal Kembali</th>
            <th>Denda</th>
            <th>Catatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php while ($d = mysqli_fetch_assoc($q)) {
            $telat = max(0, (int)((strtotime(date('Y-m-d')) - strtotime($d['tanggal_kembali'])) / 86400));
        ?>
            <tr>
                <td><?= htmlspecialchars($d['kode_sewa']) ?></td>
                <td><?= htmlspecialchars($d['nama']) ?></td>
                <td><?= htmlspecialchars($d['nama_mobil']) ?></td>
                <td><?= $d['tanggal_pengembalian'] ?: $d['tanggal_kembali'] ?></td>
                <td>Rp<?= number_format($d['status'] == 'selesai' ? $d['denda'] : $telat * 50000, 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($d['catatan'] ?? '-') ?></td>
                <td><?= $d['status'] == 'selesai' ? 'Sudah Dikembalikan' : 'Belum Dikembalikan' ?></td>
                <td>
                    <?php if ($d['status'] != 'selesai') { ?>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $d['id'] ?>">
                            <input type="date" name="tgl" min="<?= $d['tanggal_sewa'] ?>" value="<?= date('Y-m-d') ?>" required>
                            <input type="text" name="catatan" class="form-control form-control-sm my-1" placeholder="Catatan petugas">
                            <button class="btn btn-success btn-sm">Selesai</button>
                        </form>
                    <?php } else { ?>
                        ✓ Selesai
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
</div>