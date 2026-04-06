<?php
session_start();
include '../config/koneksi.php';

date_default_timezone_set("Asia/Jakarta");

$nim = $_SESSION['user']['nim'];
$token = $_POST['token'];

$tanggal = date("Y-m-d");
$jam = date("H:i:s");
$waktu = date("Y-m-d H:i:s");

// CEK TOKEN
$cekToken = $conn->query("SELECT * FROM qr_token 
WHERE token='$token' 
AND tanggal='$tanggal' 
AND status='aktif'");

if($cekToken->num_rows == 0){
    die("QR tidak valid");
}

$dataToken = $cekToken->fetch_assoc();

// CEK EXPIRED
if($jam > $dataToken['expired']){
    die("QR expired");
}

// LOGIC STATUS
$expired = strtotime($dataToken['expired']);
$mulai = strtotime("-10 minutes", $expired);
$batas = strtotime("+5 minutes", $mulai);
$sekarang = strtotime($jam);

$status = ($sekarang <= $batas) ? "hadir" : "telat";

// CEK DATA
$cek = $conn->query("SELECT * FROM absensi 
WHERE nim='$nim' AND tanggal='$tanggal'");

if($cek->num_rows > 0){

    $conn->query("UPDATE absensi 
    SET waktu='$waktu', status='$status'
    WHERE nim='$nim' AND tanggal='$tanggal'");

} else {

    $conn->query("INSERT INTO absensi(nim,tanggal,waktu,status)
    VALUES('$nim','$tanggal','$waktu','$status')");
}

echo "Absensi berhasil - $status";