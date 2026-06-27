<?php

include "koneksi.php";

$id = $_POST['id'];
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$prodi = $_POST['prodi'];
$angkatan = $_POST['angkatan'];

$query = mysqli_query($conn,

"UPDATE mahasiswa SET

nim='$nim',

nama='$nama',

prodi='$prodi',

angkatan='$angkatan'

WHERE id='$id'"

);

if($query){

header("Location:index.php");

}

?>