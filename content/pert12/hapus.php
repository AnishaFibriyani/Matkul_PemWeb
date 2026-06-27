<?php

include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($conn,

"DELETE FROM mahasiswa WHERE id='$id'"

);

if($query){

header("Location:index.php");

}

?>