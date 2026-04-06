<?php
session_start();
include '../config/koneksi.php';

if(!isset($_GET['id'])){
    die("ID tidak ditemukan!");
}

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM user WHERE id='$id'");
$data = $result->fetch_assoc();
if(isset($_POST['update'])){
    $nama = $_POST['nama'];
    $nim  = $_POST['nim'];

    // ambil nim lama langsung dari database lagi (biar pasti benar)
    $get = $conn->query("SELECT nim FROM user WHERE id='$id'");
    $old = $get->fetch_assoc();
    $nim_lama = $old['nim'];

    // update user
    $conn->query("UPDATE user SET nama='$nama', nim='$nim' WHERE id='$id'");

    // 🔥 paksa update absensi (pasti kena)
    $conn->query("UPDATE absensi SET nama='$nama', nim='$nim' WHERE nim='$nim_lama'");

    header("Location: user.php");
    exit;
}