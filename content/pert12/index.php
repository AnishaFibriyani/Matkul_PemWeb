<?php
include "koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
</head>
<body>

<h2>Data Mahasiswa</h2>

<a href="tambah.php">Tambah Data</a>

<br><br>

<table border="1" cellpadding="10">

<tr>
    <th>No</th>
    <th>NIM</th>
    <th>Nama</th>
    <th>Prodi</th>
    <th>Angkatan</th>
    <th>Aksi</th>
</tr>

<?php

$no = 1;

$data = mysqli_query($conn,"SELECT * FROM mahasiswa");

while($d = mysqli_fetch_array($data))
{

?>

<tr>

<td><?= $no++ ?></td>
<td><?= $d['nim'] ?></td>
<td><?= $d['nama'] ?></td>
<td><?= $d['prodi'] ?></td>
<td><?= $d['angkatan'] ?></td>

<td>

<a href="edit.php?id=<?=$d['id']?>">Edit</a>

|

<a href="hapus.php?id=<?=$d['id']?>"
onclick="return confirm('Yakin hapus data?')">

Hapus

</a>

</td>

</tr>

<?php
}
?>

</table>

</body>
</html>
