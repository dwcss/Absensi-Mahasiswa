<?php
session_start();
include '../config/koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['simpan'])){
    $nama = $_POST['nama'];
    $nim  = $_POST['nim'];

    // validasi
    if(empty($nama) || empty($nim)){
        echo "<script>alert('Data tidak boleh kosong!');</script>";
    } else {

        try {

            // INSERT USER
            $stmt1 = $conn->prepare("INSERT INTO `user` (nama, nim) VALUES (?, ?)");
            $stmt1->bind_param("ss", $nama, $nim);
            $stmt1->execute();

            // INSERT ABSENSI
            $stmt2 = $conn->prepare("INSERT INTO absensi (nim, nama, status, tanggal) VALUES (?, ?, 'alfa', NOW())");
            $stmt2->bind_param("ss", $nim, $nama);
            $stmt2->execute();

            // redirect
            header("Location: user.php?pesan=sukses");
            exit;

        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Mahasiswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 col-md-5">

<h4>Tambah Mahasiswa</h4>

<form action="simpan_user.php" method="POST">

<input type="text" name="nama" class="form-control mb-3" placeholder="Nama" required>
<input type="text" name="nim" class="form-control mb-3" placeholder="NIM" required>

<button type="submit" class="btn btn-success w-100">
    Simpan
</button>

</form>

</div>

</body>
</html>