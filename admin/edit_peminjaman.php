<?php
session_start();
include '../koneksi.php';

if (($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penyewaan WHERE id=$id"));
if (!$d) exit("Data tidak ditemukan.");

if (isset($_POST['simpan'])) {
    $a = $_POST['tanggal_sewa'];
    $b = $_POST['tanggal_kembali'];
    $m = (int) $_POST['mobil_id'];

    if ($b < $a) {
        $error = "Tanggal kembali tidak boleh sebelum tanggal sewa.";
    } else {
        $lama = (strtotime($b) - strtotime($a)) / 86400 + 1;
        $q = mysqli_query($conn, "SELECT harga_sewa FROM mobil WHERE id=$m");
        $harga = mysqli_fetch_assoc($q)['harga_sewa'];

        mysqli_query($conn, "UPDATE penyewaan SET mobil_id=$m,
        tanggal_sewa='$a', tanggal_kembali='$b',
        lama_sewa=$lama, total_harga=" . ($lama * $harga) . " WHERE id=$id");

        header("Location: peminjaman.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Peminjaman</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width:550px">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit Peminjaman</h5>
            </div>
            <div class="card-body">
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php } ?>
                <form method="post">
                    <label class="form-label">Pilih Mobil</label>
                    <select name="mobil_id" class="form-select mb-3" required>
                        <?php
                        $q = mysqli_query($conn, "SELECT * FROM mobil");
                        while ($m = mysqli_fetch_assoc($q)) {
                            $s = $m['id'] == $d['mobil_id'] ? 'selected' : '';
                            echo "<option value='{$m['id']}' $s>" .
                                htmlspecialchars($m['nama_mobil']) . "</option>";
                        }
                        ?>
                    </select>

                    <label class="form-label">Tanggal Sewa</label>
                    <input type="date" name="tanggal_sewa"
                        class="form-control mb-3"
                        value="<?= $d['tanggal_sewa'] ?>" required>

                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali"
                        class="form-control mb-3"
                        value="<?= $d['tanggal_kembali'] ?>" required>

                    <div class="d-flex gap-2">
                        <button name="simpan" class="btn btn-primary btn-sm">Simpan</button>
                        <a href="peminjaman.php" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>