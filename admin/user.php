<?php
session_start();
include '../config/koneksi.php';

date_default_timezone_set("Asia/Jakarta");

if(!isset($_SESSION['login_user'])){
  header("Location: login.php");
  exit;
}

$user = $_SESSION['user'];
$msg = "";

if(isset($_POST['token'])){

  $token = $_POST['token'];
  $tanggal = date("Y-m-d");
  $jam = date("H:i:s");

  // 🔥 CEK TOKEN
  $cekToken = $conn->query("SELECT * FROM qr_token
    WHERE token='$token'
    AND tanggal='$tanggal'
    AND status='aktif'");

  if($cekToken->num_rows == 0){
    $msg = "<div class='alert alert-danger'>QR tidak valid!</div>";
  } else {

    $dataToken = $cekToken->fetch_assoc();

    // 🔥 CEK EXPIRED
    if($jam > $dataToken['expired']){
      $msg = "<div class='alert alert-danger'>QR sudah expired!</div>";
    } else {

      // 🔥 CEK SUDAH ABSEN
      $cek = $conn->query("SELECT * FROM absensi
        WHERE nim='{$user['nim']}' AND tanggal='$tanggal'");

      if($cek->num_rows > 0){
        $msg = "<div class='alert alert-warning'>Sudah absen!</div>";
      } else {

        // 🔥 STATUS HADIR / TELAT
        $jam_masuk = "23:59:00";
        $status = ($jam <= $jam_masuk) ? "hadir" : "telat";

        $conn->query("INSERT INTO absensi
        (nama,nim,tanggal,waktu,status)
        VALUES
        ('{$user['nama']}','{$user['nim']}','$tanggal','$jam','$status')");

        $msg = "<div class='alert alert-success'>Absensi berhasil ($status)</div>";
      }
    }
  }
}
?>