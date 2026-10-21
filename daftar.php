<?php
session_start();
include 'koneksi.php';

$mobil = (int)($_GET['mobil'] ?? $_POST['mobil'] ?? 0);
$error = '';

if (isset($_POST['daftar'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");

    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $sql = "INSERT INTO users
                (nama, username, email, password, no_hp, alamat, role)
                VALUES ('$nama', '$username', '$email', '$password', '$hp', '$alamat', 'pelanggan')";

        if (mysqli_query($conn, $sql)) {
            if ($mobil > 0) {
                header("Location: login.php?mobil=$mobil");
            } else {
                header("Location: login.php");
            }
            exit;
        } else {
            $error = "Pendaftaran gagal!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pelanggan</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4 mb-4" style="max-width:450px">
    <div class="card p-4">
        <h3 class="text-center">Daftar Pelanggan</h3>
        <p class="text-danger"><?= $error ?></p>

        <form method="POST">
            <input type="hidden" name="mobil" value="<?= $mobil ?>">

            <input name="nama" class="form-control mb-3"
                   placeholder="Nama lengkap" required>

            <input name="username" class="form-control mb-3"
                   placeholder="Username" required>

            <input type="email" name="email" class="form-control mb-3"
                   placeholder="Email" required>

            <input type="password" name="password" class="form-control mb-3"
                   placeholder="Password" required>

            <input name="no_hp" class="form-control mb-3"
                   placeholder="Nomor HP" required>

            <textarea name="alamat" class="form-control mb-3"
                      placeholder="Alamat" required></textarea>

            <button name="daftar" class="btn btn-primary w-100">
                Daftar
            </button>
        </form>

        <p class="text-center mt-3">
            Sudah punya akun?
            <a href="login.php?mobil=<?= $mobil ?>">Login</a>
        </p>
    </div>
</div>

</body>
</html>