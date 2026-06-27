<?php

include "koneksi.php";

$id = $_GET['id'];

$data = mysqli_query($conn,"SELECT * FROM mahasiswa WHERE id='$id'");

$d = mysqli_fetch_array($data);

?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Mahasiswa</title>
</head>

<body>

<h2>Edit Mahasiswa</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $d['id'] ?>">

NIM :

<input type="text" name="nim" value="<?= $d['nim'] ?>">

<br><br>

Nama :

<input type="text" name="nama" value="<?= $d['nama'] ?>">

<br><br>

Prodi :

<input type="text" name="prodi" value="<?= $d['prodi'] ?>">

<br><br>

Angkatan :

<input type="number" name="angkatan" value="<?= $d['angkatan'] ?>">

<br><br>

<button type="submit">

Update

</button>

</form>

</body>
</html>