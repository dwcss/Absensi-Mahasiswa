<?php
session_start();
include '../config/koneksi.php';
include '../config/auto_alfa.php';

date_default_timezone_set("Asia/Jakarta");

if(!isset($_SESSION['login_admin'])){
    header("Location: login.php");
    exit;
}

$menu = isset($_GET['menu']) ? $_GET['menu'] : 'dashboard';

// 🔥 DATA DASHBOARD
$tanggal = date("Y-m-d");
$totalUser = $conn->query("SELECT COUNT(*) as t FROM user")->fetch_assoc()['t'];
$totalAbsen = $conn->query("SELECT COUNT(*) as t FROM absensi WHERE tanggal='$tanggal'")->fetch_assoc()['t'];
$hadir = $conn->query("SELECT COUNT(*) as t FROM absensi WHERE tanggal='$tanggal' AND status='hadir'")->fetch_assoc()['t'];
$telat = $conn->query("SELECT COUNT(*) as t FROM absensi WHERE tanggal='$tanggal' AND status='telat'")->fetch_assoc()['t'];
$alfa = $conn->query("SELECT COUNT(*) as t FROM absensi WHERE tanggal='$tanggal' AND status='alfa'")->fetch_assoc()['t'];
$tokenAktif = $conn->query("SELECT * FROM qr_token WHERE tanggal='$tanggal' AND status='aktif' ORDER BY id DESC LIMIT 1");
$dataToken = $tokenAktif->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* BACKGROUND */
body { 
    background: linear-gradient(135deg, #eef2f3, #dfe9f3);
}

/* NAVBAR */
.navbar { 
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    backdrop-filter: blur(12px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
}

/* HERO FUTURISTIK */
.hero {
    position: relative;
    height: 180px;
    border-radius: 20px;
    margin-bottom: 25px;
    padding: 30px;

    background: linear-gradient(135deg, #0f2027, #2c5364);
    overflow: hidden;

    color: white;
    display: flex;
    align-items: center;
}

/* efek glow */
.hero::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;

    background: radial-gradient(circle at top right, rgba(0,198,255,0.3), transparent 60%),
                radial-gradient(circle at bottom left, rgba(118,75,162,0.3), transparent 60%);

    animation: glowMove 6s infinite alternate;
}

/* biar teks di atas */
.hero-text {
    position: relative;
    z-index: 2;
}

.hero-text h2 {
    font-weight: bold;
    margin-bottom: 5px;
}

.hero-text p {
    opacity: 0.9;
}

/* ANIMASI */
@keyframes glowMove {
    0% { opacity: 0.6; }
    100% { opacity: 1; }
}

/* MENU */
.menu-nav {
    display: flex;
    gap: 10px;
    margin-top: 10px;
    flex-wrap: wrap;
}

/* BUTTON */
.nav-btn {
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 14px;

    color: #e2e8f0;
    background: rgba(255,255,255,0.08);

    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);

    transition: all 0.3s ease;
}

/* HOVER */
.nav-btn:hover {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 0 12px rgba(0,198,255,0.7);
}

/* ACTIVE */
.nav-btn.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    font-weight: bold;
    box-shadow: 0 0 15px rgba(118,75,162,0.8);
}

/* LOGOUT */
.nav-btn.logout {
    background: linear-gradient(135deg, #ff7e5f, #eb3349);
    color: white;
}

.nav-btn.logout:hover {
    box-shadow: 0 0 15px rgba(255,80,80,0.7);
}

/* CARD */
.card-stat { 
    border-radius:18px; 
    padding:20px; 
    color:white; 
    transition: 0.3s;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.card-stat:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.25);
}

/* GRADIENT CARD */
.blue   { background: linear-gradient(135deg,#1e3a8a,#0a1a2f); }
.green  { background: linear-gradient(135deg,#15803d,#064e3b); }
.orange { background: linear-gradient(135deg,#ea580c,#9a3412); }
.yellow { background: linear-gradient(135deg,#eab308,#ca8a04); }
.red    { background: linear-gradient(135deg,#ef4444,#7f1d1d); }

/* BOX */
.box { 
    background: #f5efe6;
    padding:20px; 
    border-radius:15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* TABLE */
table thead {
    background:#0a1a2f;
    color:white;
}

table tbody tr {
    background:#f5efe6 !important;
}

table td, table th {
    border-color: #d8d1c6 !important;
}

/* STATUS */
.status-hadir { color:#0f5132 !important; font-weight:bold; }
.status-telat { color:#facc15 !important; font-weight:bold; }
.status-alfa  { color:#b91c1c !important; font-weight:bold; }

/* TITLE */
h4 {
    font-weight: bold;
    color: #0a1a2f;
}

/* FOOTER */
.footer {
    margin-top: 50px;
    padding: 20px 0;

    background: linear-gradient(135deg, #0f2027, #2c5364);
    color: #e2e8f0;

    border-top: 1px solid rgba(255,255,255,0.1);
}

/* CONTAINER BIAR GA NEMPEL */
.footer-container {
    max-width: 1100px;
    margin: auto;
    padding: 0 20px;
}

/* ISI FOOTER */
.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;

    font-size: 14px;
}

/* NAMA KAMU GLOW */
.footer strong {
    color: #00c6ff;
    text-shadow: 0 0 8px rgba(0,198,255,0.7);
}
/* FOOTER FULL WIDTH */
.footer {
    width: 100%;          /* 🔥 ini penting */
    margin-top: 50px;
    padding: 20px 0;

    background: linear-gradient(135deg, #0f2027, #2c5364);
    color: #e2e8f0;

    border-top: 1px solid rgba(255,255,255,0.1);
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="hero">
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <h2>📊 Sistem Absensi Digital</h2>
        <p>Monitoring kehadiran mahasiswa secara real-time</p>

        <div class="menu-nav">
            <a href="index.php?menu=dashboard" class="nav-btn <?= $menu=='dashboard'?'active':'' ?>">🏠 Dashboard</a>
            <a href="index.php?menu=user" class="nav-btn <?= $menu=='user'?'active':'' ?>">👤 User</a>
            <a href="index.php?menu=rekap" class="nav-btn <?= $menu=='rekap'?'active':'' ?>">📊 Rekap</a>
            <a href="logout.php" class="nav-btn logout">🚪 Logout</a>
        </div>
    </div>
</div>
</nav>

<div class="container mt-4">

<?php
// ================= DASHBOARD =================
if($menu == 'dashboard'){
?>

<h4 class="mb-4">Dashboard</h4>

<div class="row mb-4">
<div class="row mb-4">

<<div class="row mb-4">

<div class="col-md-2">
    <div class="card-stat blue">
        <h6>Total User</h6>
        <h2><?= $totalUser ?></h2>
    </div>
</div>

<div class="col-md-2">
    <div class="card-stat green">
        <h6>Absen Hari Ini</h6>
        <h2><?= $totalAbsen ?></h2>
    </div>
</div>

<div class="col-md-2">
    <div class="card-stat orange">
        <h6>Hadir</h6>
        <h2><?= $hadir ?></h2>
    </div>
</div>

<div class="col-md-2">
    <div class="card-stat yellow">
        <h6>Telat</h6>
        <h2><?= $telat ?></h2>
    </div>
</div>

<div class="col-md-2">
    <div class="card-stat red">
        <h6>Alfa</h6>
        <h2><?= $alfa ?></h2>
    </div>
</div>

</div>
</div>

<div class="box shadow-sm">
<h5>Generate QR</h5>

<form method="POST" action="generate.php">
<input type="time" name="expired" class="form-control mb-2" required>
<button class="btn btn-primary w-100">Generate</button>
</form>

<?php if($dataToken){ ?>
<hr>
<div class="text-center">
<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $dataToken['token']; ?>">
<h5 class="mt-2"><?= $dataToken['token'] ?></h5>
<small>Expired: <?= $dataToken['expired'] ?></small><br>

<a href="nonaktifkan.php?id=<?= $dataToken['id'] ?>" 
class="btn btn-danger btn-sm mt-2"
onclick="return confirm('Nonaktifkan QR?')">
Nonaktifkan
</a>
</div>
<?php } ?>
</div>

<?php
}

// ================= USER =================
elseif($menu == 'user'){
$data = $conn->query("SELECT * FROM user ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="mb-0">Data Mahasiswa</h4>

    <div>
        <a href="index.php?menu=dashboard" class="btn btn-secondary btn-sm">
            ← Kembali
        </a>

        <a href="index.php?menu=tambah_user" class="btn btn-primary btn-sm">
            + Tambah
        </a>
    </div>

</div>
<div class="box">
<table class="table table-bordered">
<tr><th>No</th><th>Nama</th><th>NIM</th><th>Aksi</th></tr>

<?php $no=1; while($d=$data->fetch_assoc()){ ?>
<tr>
<td><?= $no++ ?></td>
<td><?= $d['nama'] ?></td>
<td><?= $d['nim'] ?></td>
<td>
<a href="index.php?menu=edit_user&id=<?= $d['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="hapus_user.php?id=<?= $d['id'] ?>&nim=<?= $d['nim'] ?>" 
   class="btn btn-danger btn-sm" 
   onclick="return confirm('Hapus?')">
   Hapus
</a>
</td>
</tr>
<?php } ?>

</table>
</div>

<?php
}

// ================= TAMBAH =================
elseif($menu == 'tambah_user'){
?>

<h4>Tambah Mahasiswa</h4>

<form method="POST" action="simpan_user.php" class="box col-md-5">
<input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required>
<input type="text" name="nim" class="form-control mb-2" placeholder="NIM" required>

<button class="btn btn-success w-100">Simpan</button>
<a href="index.php?menu=user" class="btn btn-secondary w-100 mt-2">← Kembali</a>
</form>

<?php
}

// ================= EDIT =================
elseif($menu == 'edit_user'){

if(!isset($_GET['id'])){
    echo "<div class='alert alert-danger'>ID tidak ditemukan</div>";
} else {

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM user WHERE id='$id'")->fetch_assoc();
?>

<h4>Edit Mahasiswa</h4>

<form method="POST" action="update_user.php" class="box col-md-5">
<input type="hidden" name="id" value="<?= $data['id'] ?>">
<input type="text" name="nama" value="<?= $data['nama'] ?>" class="form-control mb-2" required>
<input type="text" name="nim" value="<?= $data['nim'] ?>" class="form-control mb-2" required>

<button class="btn btn-primary w-100">Update</button>
<a href="index.php?menu=user" class="btn btn-secondary w-100 mt-2">← Kembali</a>
</form>

<?php
}
}

// ================= REKAP =================
elseif($menu == 'rekap'){
$data = $conn->query("SELECT * FROM absensi ORDER BY id DESC");
?>

<h4>Rekap Absensi</h4>
<a href="export_excel.php" class="btn btn-success mb-3">
    ⬇ Export Excel
</a>

<div class="box">

<!-- TAMBAHKAN rekap-table -->
<table class="table table-bordered rekap-table">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>NIM</th>
    <th>Tanggal</th>
    <th>Waktu</th>
    <th>Status</th>
</tr>

<?php 
$no=1; 
while($d=$data->fetch_assoc()){

    // buat class berdasarkan status (hadir/izin/alpa/telat)
    $statusClass = strtolower(trim($d['status']));
?>
<tr class="<?= $statusClass ?>">
    <td><?= $no++ ?></td>
    <td><?= $d['nama'] ?></td>
    <td><?= $d['nim'] ?></td>
    <td><?= $d['tanggal'] ?></td>
    <td><?= date('H:i', strtotime($d['waktu'])) ?></td>
    <td class="status-<?= strtolower($d['status']) ?>">
    <?= ucfirst($d['status']) ?></td>
</tr>

<?php } ?>

</table>
</div>

<?php
}

// ================= ERROR =================
else{
    echo "<div class='alert alert-danger'>Menu tidak ditemukan!</div>";
}
?>

</div>
<body>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <p>© 2026 Sistem Absensi Digital</p>
            <span>Production by <strong>Dawwas</strong> 🚀</span>
        </div>
    </div>
</footer>
</div>
</body>
</html>
