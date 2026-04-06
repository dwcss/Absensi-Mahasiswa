<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['login_user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// 🔥 ambil data absensi user
$data = $conn->query("SELECT * FROM absensi 
    WHERE nim='{$user['nim']}'
    ORDER BY tanggal DESC, waktu DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Riwayat Absensi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#f4f7fb;
}

/* navbar */
.navbar {
    background: linear-gradient(90deg,#2563eb,#1d4ed8);
}

/* card */
.card-box {
    background:white;
    border-radius:15px;
    padding:20px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark p-3">
<div class="container d-flex justify-content-between">

<span class="text-white fw-bold">📋 Absensi Online</span>

<div>
<a href="index.php" class="btn btn-light btn-sm">Home</a>
<a href="absen.php" class="btn btn-success btn-sm">Absen</a>
<a href="riwayat.php" class="btn btn-info btn-sm">Riwayat</a>
<a href="logout.php" class="btn btn-warning btn-sm">Logout</a>
</div>

</div>
</nav>

<div class="container mt-4">

<h4 class="mb-3">Riwayat Kehadiran</h4>

<div class="card-box shadow-sm">

<div class="table-responsive">
<table class="table table-bordered table-hover">

<thead class="table-light">
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Waktu</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php 
$no=1;
if($data->num_rows > 0){
while($d=$data->fetch_assoc()){ 
?>
<tr>
    <td><?= $no++ ?></td>
   <td><?= date('d M Y', strtotime($d['tanggal'])) ?></td>
    <td><?= date('H:i', strtotime($d['waktu'])) ?> WIB</td>
    <td>
        <?php if($d['status']=="telat"){ ?>
            <span class="badge bg-warning text-dark">Telat</span>
        <?php } else { ?>
            <span class="badge bg-success">Hadir</span>
        <?php } ?>
    </td>
</tr>
<?php } } else { ?>
<tr>
    <td colspan="4" class="text-center">Belum ada data absensi</td>
</tr>
<?php } ?>
</tbody>

</table>
</div>

</div>

</div>

</body>
</html>