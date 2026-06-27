<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "pert12";

$conn = mysqli_connect($db_host,$db_user,$db_pass,$db_name);

if(!$conn){
die("Koneksi gagal : ".mysqli_connect_error());
}

?>