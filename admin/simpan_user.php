<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/koneksi.php';

// ambil data (AMAN walaupun kosong)
$nama = $_POST['nama'] ?? '';
$nim  = $_POST['nim'] ?? '';

// validasi
if ($nama == '' || $nim == '') {
    die("Data tidak boleh kosong!");
}

try {

    // simpan ke tabel user
    $stmt1 = $conn->prepare("INSERT INTO `user` (nama, nim) VALUES (?, ?)");
    $stmt1->bind_param("ss", $nama, $nim);
    $stmt1->execute();

    // simpan ke tabel absensi
    $stmt2 = $conn->prepare("INSERT INTO absensi (nim, nama, status, tanggal) VALUES (?, ?, 'alfa', NOW())");
    $stmt2->bind_param("ss", $nim, $nama);
    $stmt2->execute();

    // redirect (WAJIB terakhir)
    header("Location: index.php?menu=user");
    exit;

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}