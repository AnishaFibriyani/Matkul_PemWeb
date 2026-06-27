<?php

include "koneksi.php";

$nim = $_POST['nim'];
$nama = $_POST['nama'];
$prodi = $_POST['prodi'];
$angkatan = $_POST['angkatan'];

$query = mysqli_query($conn,

"INSERT INTO mahasiswa
(nim,nama,prodi,angkatan)
VALUES
('$nim','$nama','$prodi','$angkatan')");

if($query){

header("Location:index.php");

}

?>