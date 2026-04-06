<?php
include '../config/koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$nim = $_POST['nim'];

// 🔥 ambil data lama
$get = $conn->query("SELECT * FROM user WHERE id='$id'");
$data_lama = $get->fetch_assoc();

$nama_lama = $data_lama['nama'];
$nim_lama = $data_lama['nim'];

// 🔥 update user
$conn->query("UPDATE user SET nama='$nama', nim='$nim' WHERE id='$id'");

// 🔥 update absensi pakai data lama
$conn->query("UPDATE absensi SET nama='$nama', nim='$nim' WHERE nim='$nim_lama'");

header("Location: index.php?menu=user");
?>