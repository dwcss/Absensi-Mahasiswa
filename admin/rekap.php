<?php
include '../config/koneksi.php';

$data = $conn->query("
SELECT u.nama, u.nim,
IFNULL(a.status, 'alfa') as status,
a.tanggal, a.waktu
FROM user u
LEFT JOIN absensi a 
ON u.nim = a.nim
ORDER BY a.tanggal DESC
");
?>

<table border="1">
<tr>
<th>Nama</th>
<th>NIM</th>
<th>Tanggal</th>
<th>Waktu</th>
<th>Status</th>
</tr>

<?php while($d = $data->fetch_assoc()){ ?>
<tr>
<td><?= $d['nama'] ?></td>
<td><?= $d['nim'] ?></td>
<td><?= $d['tanggal'] ?></td>
<td><?= $d['waktu'] ?></td>
<td><?= $d['status'] ?></td>
</tr>
<?php } ?>
</table>