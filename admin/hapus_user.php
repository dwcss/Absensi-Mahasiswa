<?php
include '../config/koneksi.php';

$id = (int) $_GET['id'];
$nim = (int) $_GET['nim'];

// hapus user
$conn->query("DELETE FROM user WHERE id=$id");

// hapus absensi
$hapus = $conn->query("DELETE FROM absensi WHERE nim=$nim");

if(!$hapus){
    die("Error hapus absensi: " . $conn->error);
}

header("Location: index.php?menu=user");
?>