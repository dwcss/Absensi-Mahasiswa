<?php
include 'koneksi.php';

date_default_timezone_set("Asia/Jakarta");

$tanggal = date("Y-m-d");
$jam_sekarang = date("H:i:s");

$jam_masuk = "01:00:00";
$batas_akhir = date("H:i:s", strtotime($jam_masuk) + 3600); // +1 jam

// kalau sudah lewat 1 jam
if($jam_sekarang > $batas_akhir){

    // ambil semua user yg BELUM absen hari ini
    $user = $conn->query("SELECT * FROM user 
        WHERE nim NOT IN (
            SELECT nim FROM absensi WHERE tanggal='$tanggal'
        )");

    while($u = $user->fetch_assoc()){

        $waktu = date("Y-m-d H:i:s");

        $conn->query("INSERT INTO absensi
        (nama,nim,tanggal,waktu,status)
        VALUES
        ('{$u['nama']}','{$u['nim']}','$tanggal','$waktu','alfa')");
    }
}
?>