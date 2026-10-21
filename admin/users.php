<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$q = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Users</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3>Kelola Users</h3>

        <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
        <a href="tambah_user.php" class="btn btn-primary btn-sm">Tambah User</a>

        <table class="table table-bordered table-striped mt-3">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            <?php $no = 1;
            while ($u = mysqli_fetch_assoc($q)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($u['nama']) ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $u['id'] ?>"
                            class="btn btn-warning btn-sm">Edit</a>

                        <a href="hapus_user.php?id=<?= $u['id'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>

</html>