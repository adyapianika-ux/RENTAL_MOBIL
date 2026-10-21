<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$q = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($q);

if (!$user) {
    die("User tidak ditemukan!");
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $password = "";
    if ($_POST['password'] != "") {
        $password = ", password='" .
            password_hash($_POST['password'], PASSWORD_DEFAULT) . "'";
    }

    $cek = mysqli_query($conn, "SELECT id FROM users
        WHERE username='$username' AND id!=$id");

    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Username sudah digunakan!";
    } else {
        $update = mysqli_query($conn, "UPDATE users SET
            nama='$nama',
            username='$username',
            email='$email',
            no_hp='$no_hp',
            alamat='$alamat',
            role='$role'
            $password
            WHERE id=$id");

        if ($update) {
            header("Location: users.php");
            exit;
        } else {
            $pesan = "Gagal mengubah data user!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3>Edit User</h3>

        <div class="card p-4">
            <?php if (isset($pesan)) { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($pesan) ?>
                </div>
            <?php } ?>

            <form method="POST">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control mb-3"
                    value="<?= htmlspecialchars($user['nama']) ?>" required>

                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control mb-3"
                    value="<?= htmlspecialchars($user['username']) ?>" required>

                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control mb-3"
                    value="<?= htmlspecialchars($user['email']) ?>" required>

                <label class="form-label">Password baru (kosongkan jika tidak diganti)</label>
                <input type="password" name="password" class="form-control mb-3">

                <label class="form-label">Nomor HP</label>
                <input type="text" name="no_hp" class="form-control mb-3"
                    value="<?= htmlspecialchars($user['no_hp']) ?>" required>

                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control mb-3" required><?= htmlspecialchars($user['alamat']) ?></textarea>

                <label class="form-label">Role</label>
                <select name="role" class="form-select mb-3" required>
                    <option value="pelanggan" <?= $user['role'] == 'pelanggan' ? 'selected' : '' ?>>Pelanggan</option>
                    <option value="petugas" <?= $user['role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                <a href="users.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>

</body>

</html>