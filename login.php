<?php
session_start();
$mobil = (int)($_GET['mobil'] ?? 0);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login SiRentCar</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width:400px">
        <div class="card p-4">
            <h3 class="text-center">Login SiRentCar</h3>

            <form action="proses_login.php" method="POST">
                <input type="hidden" name="mobil" value="<?= $mobil ?>">
                <input type="text" name="username" class="form-control mb-3"
                    placeholder="Username" required>
                <input type="password" name="password" class="form-control mb-3"
                    placeholder="Password" required>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3">
                Belum punya akun? <a href="daftar.php?mobil=<?= $mobil ?>">Daftar dulu</a>
            </p>
            <a href="index.php" class="text-center">Kembali ke Beranda</a>
        </div>
    </div>
</body>

</html>