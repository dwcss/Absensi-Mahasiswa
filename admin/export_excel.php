<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['login_admin'])){
    header("Location: login.php");
    exit;
}

// header excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Rekap_Absensi.xls");

?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NIM</th>
        <th>Tanggal</th>
        <th>Waktu</th>
        <th>Status</th>
    </tr>

<?php
$no = 1;
$data = $conn->query("SELECT * FROM absensi ORDER BY tanggal DESC, waktu DESC");

while($d = $data->fetch_assoc()){
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['nama'] ?></td>
    <td><?= $d['nim'] ?></td>
    <td><?= $d['tanggal'] ?></td>
    <td><?= date("H:i:s", strtotime($d['waktu'])) ?></td>
    <td><?= $d['status'] ?></td>
</tr>
<?php } ?>

</table>