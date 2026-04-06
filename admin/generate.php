<?php
session_start();
include '../config/koneksi.php';

date_default_timezone_set("Asia/Jakarta");

// 🔒 CEK LOGIN ADMIN
if(!isset($_SESSION['login_admin'])){
  header("Location: login.php");
  exit;
}

// 🔥 DEFAULT DURASI
$durasi = 10;

// 🔥 JIKA FORM DIKLIK
if(isset($_POST['generate'])){

    $durasi = (int)$_POST['durasi'];

    // minimal 1 menit biar aman
    if($durasi <= 0){
        $durasi = 10;
    }

    // 🔥 NONAKTIFKAN TOKEN LAMA
    $conn->query("UPDATE qr_token SET status='tidak'");

    // 🔥 BUAT TOKEN BARU
    $token = strtoupper(bin2hex(random_bytes(3)));
    $tanggal = date("Y-m-d");

    // 🔥 HITUNG EXPIRED
    $expired = date("H:i:s", strtotime("+$durasi minutes"));

    // 🔥 SIMPAN KE DATABASE
    $conn->query("INSERT INTO qr_token(token,tanggal,expired,status)
    VALUES('$token','$tanggal','$expired','aktif')");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Generate QR</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f4f7fb; }
.navbar { background:linear-gradient(90deg,#2563eb,#1d4ed8); }
.box {
    background:white;
    padding:30px;
    border-radius:15px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark p-3">
<div class="container d-flex justify-content-between">

<span class="text-white fw-bold">⚙️ Admin Panel</span>

<div>
<a href="index.php?menu=dashboard" class="btn btn-light btn-sm">Dashboard</a>
<a href="index.php?menu=user" class="btn btn-light btn-sm">User</a>
<a href="index.php?menu=rekap" class="btn btn-info btn-sm">Rekap</a>
<a href="logout.php" class="btn btn-warning btn-sm">Logout</a>
</div>

</div>
</nav>

<div class="container mt-4">

<h4 class="mb-4">Generate QR Absensi</h4>

<div class="box shadow-sm text-center">

    <!-- 🔥 FORM DURASI -->
    <form method="POST" class="mb-4">
        <label>Durasi QR (menit)</label>
        <input type="number" name="durasi" class="form-control mb-3" value="<?= $durasi ?>" required>

        <button type="submit" name="generate" class="btn btn-primary w-100">
            Generate QR
        </button>
    </form>

    <?php if(isset($token)) { ?>

        <h5 class="mb-3">QR Aktif</h5>

        <img 
            src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= $token ?>"
            class="mb-3"
        >

        <h4><?= $token ?></h4>

        <p class="text-muted">
            Berlaku sampai: <b><?= $expired ?></b>
        </p>

        <p class="text-muted">
            Durasi: <b><?= $durasi ?> menit</b>
        </p>

    <?php } ?>

    <div class="d-grid gap-2 mt-3">

        <a href="index.php?menu=dashboard" class="btn btn-secondary">
            ← Kembali ke Dashboard
        </a>

    </div>

</div>

</div>

</body>
</html>