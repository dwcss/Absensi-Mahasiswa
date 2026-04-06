<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Absensi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
body {
    background: linear-gradient(120deg,#eef2ff,#e0f2fe);
}
.card-box {
    border-radius: 20px;
    padding: 20px;
    background: white;
}
#reader {
    border-radius: 15px;
    overflow: hidden;
    display: none;
}
.token-box {
    font-size: 18px;
    font-weight: bold;
    color: #2563eb;
}
</style>
</head>

<body>

<div class="container mt-5">
<div class="card-box shadow col-md-5 mx-auto">

<h4 class="text-center mb-4">📌 Absensi</h4>

<!-- 🔘 PILIH MODE -->
<div class="d-flex gap-2 mb-3">
    <button class="btn btn-primary w-50" onclick="modeScan()">📷 Scan QR</button>
    <button class="btn btn-secondary w-50" onclick="modeManual()">✍️ Input Token</button>
</div>

<form method="POST" action="proses_absen.php">

    <!-- 🔍 SCANNER -->
    <div id="reader" class="mb-3"></div>

    <!-- TOKEN HASIL SCAN -->
    <div id="hasil_scan" class="text-center mb-3 token-box"></div>

    <!-- ✍️ INPUT MANUAL -->
    <input type="text" id="input_token" name="token"
        class="form-control mb-3 text-center"
        placeholder="Masukkan Token"
        style="display:none; text-transform:uppercase;">

    <button type="submit" class="btn btn-success w-100">
        Kirim Absensi
    </button>

</form>

</div>
</div>

<script>
let scanner;
let isScanning = false;

// MODE SCAN
function modeScan(){
    document.getElementById('reader').style.display = 'block';
    document.getElementById('input_token').style.display = 'none';

    if(!isScanning){
        scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });

        scanner.render(function(decodedText){

            document.getElementById('hasil_scan').innerText = "Token: " + decodedText;
            document.getElementById('input_token').value = decodedText;

            scanner.clear();

        });

        isScanning = true;
    }
}

// MODE MANUAL
function modeManual(){
    document.getElementById('reader').style.display = 'none';
    document.getElementById('input_token').style.display = 'block';
}

// AUTO UPPERCASE
document.addEventListener("input", function(e){
    if(e.target.id === "input_token"){
        e.target.value = e.target.value.toUpperCase();
    }
});
</script>

</body>
</html>