<?php
session_start();
include '../config/koneksi.php';

date_default_timezone_set("Asia/Jakarta");

if(!isset($_SESSION['user'])){ // 🔥 samakan dengan login
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

$tanggal = date("Y-m-d");

// 🔥 cek absen hari ini
$cek = $conn->query("SELECT * FROM absensi 
WHERE nim='{$user['nim']}' AND tanggal='$tanggal'");

// 🔥 ambil statistik kecil
$total = $conn->query("SELECT COUNT(*) as t FROM absensi WHERE nim='{$user['nim']}'")->fetch_assoc()['t'];
$hadir = $conn->query("SELECT COUNT(*) as t FROM absensi WHERE nim='{$user['nim']}' AND status='hadir'")->fetch_assoc()['t'];
$telat = $conn->query("SELECT COUNT(*) as t FROM absensi WHERE nim='{$user['nim']}' AND status='telat'")->fetch_assoc()['t'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(120deg, #eef2ff, #e0f2fe);
    font-family: 'Segoe UI', sans-serif;
}

/* NAVBAR */
.navbar {
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #ddd;
}

/* HERO */
.hero {
    background: linear-gradient(135deg,#6366f1,#22c55e);
    color: white;
    padding: 25px;
    border-radius: 20px;
}

/* CARD MENU */
.menu-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    text-align: center;
    transition: 0.3s;
    border: 1px solid #eee;
}

.menu-card:hover {
    transform: scale(1.03);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* STAT BOX */
.stat-box {
    background:white;
    padding:15px;
    border-radius:15px;
    text-align:center;
    border:1px solid #eee;
}

.icon {
    font-size: 35px;
    margin-bottom: 10px;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar p-3">
  <div class="container d-flex justify-content-between">
    <strong>📊 Sistem Absensi</strong>

    <div>
      <a href="index.php" class="btn btn-outline-dark btn-sm">Home</a>
      <a href="absen.php" class="btn btn-success btn-sm">Absen</a>
      <a href="riwayat.php" class="btn btn-primary btn-sm">Riwayat</a>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">

    <!-- HERO -->
    <div class="hero mb-4">
        <h4>Halo, <?= $user['nama']; ?> 👋</h4>
        <small>NIM: <?= $user['nim']; ?></small>
    </div>

    <!-- STATUS -->
    <?php 
if($cek->num_rows > 0){ 
    $data = $cek->fetch_assoc();
    $status = $data['status'];

    if($status == 'hadir'){
        echo "<div class='alert alert-success'>
        ✅ Status hari ini: <b>HADIR</b>
        <br>Jam: {$data['waktu']}
        </div>";
    } elseif($status == 'telat'){
        echo "<div class='alert alert-warning'>
        ⏰ Status hari ini: <b>TELAT</b>
        <br>Jam: {$data['waktu']}
        </div>";
    }

} else {
    echo "<div class='alert alert-danger'>
    ❌ Status hari ini: <b>ALFA</b>
    </div>";
}
?>

    <!-- 🔥 STATISTIK MINI -->
    <div class="row mb-4 text-center">
        <div class="col-md-4 mb-2">
            <div class="stat-box">
                <h6>Total Absen</h6>
                <h4><?= $total ?></h4>
            </div>
        </div>

        <div class="col-md-4 mb-2">
            <div class="stat-box">
                <h6>Hadir</h6>
                <h4 class="text-success"><?= $hadir ?></h4>
            </div>
        </div>

        <div class="col-md-4 mb-2">
            <div class="stat-box">
                <h6>Telat</h6>
                <h4 class="text-danger"><?= $telat ?></h4>
            </div>
        </div>
    </div>

    <!-- MENU -->
    <div class="row">

        <div class="col-md-6 mb-3">
            <div class="menu-card">
                <div class="icon">📷</div>
                <h5>Absensi QR</h5>
                <p>Scan QR dari admin untuk melakukan absensi</p>

                <a href="absen.php" class="btn btn-dark w-100">
                    Mulai Scan
                </a>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="menu-card">
                <div class="icon">📈</div>
                <h5>Riwayat Kehadiran</h5>
                <p>Lihat semua data kehadiran kamu</p>

                <a href="riwayat.php" class="btn btn-success w-100">
                    Lihat Data
                </a>
            </div>
        </div>

    </div>

</div>

</body>
</html>