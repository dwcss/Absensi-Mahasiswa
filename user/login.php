<?php
session_start();
include '../config/koneksi.php';

date_default_timezone_set("Asia/Jakarta");

// ⛔ biar ga loop
if(isset($_SESSION['user'])){
    header("Location: index.php");
    exit;
}

if(isset($_POST['login'])){

  $nim = (int) $_POST['nim'];

  $cek = $conn->query("SELECT * FROM user WHERE nim=$nim");

  if($cek && $cek->num_rows > 0){
    $data = $cek->fetch_assoc();

    $_SESSION['login_user'] = true;
    $_SESSION['user'] = $data;

    header("Location: index.php");
    exit;

  } else {
    $error = "NIM tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Absensi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-primary bg-gradient">

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
  <div class="card p-4 shadow" style="width:350px; border-radius:15px;">
    
    <h4 class="text-center mb-3">Login Absensi</h4>

    <?php if(isset($error)){ ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php } ?>

    <form method="POST">
      <input type="text" name="nim" class="form-control mb-3" placeholder="Masukkan NIM" required>
      <button name="login" class="btn btn-primary w-100">Login</button>
    </form>

  </div>
</div>

</body>
</html>