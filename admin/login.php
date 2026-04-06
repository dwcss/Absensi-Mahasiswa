<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/koneksi.php';

if(isset($_POST['login'])){
  $u = mysqli_real_escape_string($conn, $_POST['username']);
  $p = md5($_POST['password']);
  $captcha = $_POST['captcha'];

  if(!isset($_SESSION['captcha'])){
    $error = "Captcha tidak tersedia!";
  } 
  elseif(strtolower($captcha) != strtolower($_SESSION['captcha'])){
    $error = "Captcha salah!";
  } 
  else {

    $cek = $conn->query("SELECT * FROM admin 
      WHERE username='$u' AND password='$p'");

    if($cek && $cek->num_rows > 0){
      $_SESSION['login_admin'] = true;
      $_SESSION['admin'] = $cek->fetch_assoc();

      header("Location: index.php");
      exit;
    } else {
      $error = "Username / Password salah!";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  background: linear-gradient(135deg, #141e30, #243b55);
  color: white;
}
.card {
  border-radius: 15px;
  background: #1f2a40;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">

<div class="card p-4 shadow" style="width:350px;">

<h4 class="text-center mb-3">🔐 Login Admin</h4>

<?php if(isset($error)){ ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php } ?>

<form method="POST">

<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>

<input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

<div class="mb-3 text-center">
  <img src="captcha.php" id="captchaImg" class="mb-2" style="cursor:pointer;" onclick="refreshCaptcha()">
  <input type="text" name="captcha" class="form-control" placeholder="Masukkan captcha" required>
  <small>Klik gambar untuk refresh</small>
</div>

<button name="login" class="btn btn-primary w-100">Login</button>

</form>

</div>
</div>

<script>
function refreshCaptcha(){
  document.getElementById('captchaImg').src = 'captcha.php?' + Date.now();
}
</script>

</body>
</html>