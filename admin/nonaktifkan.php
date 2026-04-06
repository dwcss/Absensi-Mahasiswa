<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['login_admin'])){
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // 🔥 ubah status jadi tidak aktif
    $conn->query("UPDATE qr_token SET status='tidak' WHERE id='$id'");
}

// balik ke dashboard
header("Location: index.php?menu=dashboard");
exit;
?>