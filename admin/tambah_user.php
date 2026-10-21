<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");

    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Username sudah digunakan!";
    } else {
        $simpan = mysqli_query($conn, "INSERT INTO users
            (nama, username, email, password, no_hp, alamat, role)
            VALUES ('$nama', '$username', '$email', '$password', '$no_hp', '$alamat', '$role')");

        if ($simpan) {
            header("Location: users.php");
            exit;
        } else {
            $pesan = "Gagal menambahkan user!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah User</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3>Tambah User</h3>

    <div class="card p-4">
        <?php if (isset($pesan)) { ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php } ?>

        <form method="POST">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control mb-3" required>

            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control mb-3" required>

            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control mb-3" required>

            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control mb-3" required>

            <label class="form-label">Nomor HP</label>
            <input type="text" name="no_hp" class="form-control mb-3" required>

            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control mb-3" required></textarea>

            <label class="form-label">Role</label>
            <select name="role" class="form-select mb-3" required>
                <option value="pelanggan">Pelanggan</option>
                <option value="petugas">Petugas</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="users.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

</body>
</html>