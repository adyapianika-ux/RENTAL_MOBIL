<?php
session_start();
include '../koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM log_aktivitas ORDER BY waktu DESC");
?>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<div class="container mt-4">
    <h3>Log Aktivitas</h3>
    <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>

    <table class="table table-bordered mt-3">
        <tr>
            <th>ID User</th>
            <th>Aktivitas</th>
            <th>Waktu</th>
        </tr>
        <?php while ($d = mysqli_fetch_assoc($q)): ?>
            <tr>
                <td><?= $d['user_id'] ?></td>
                <td><?= htmlspecialchars($d['aktivitas']) ?></td>
                <td><?= $d['waktu'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>