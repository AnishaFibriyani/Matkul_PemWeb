<!DOCTYPE html>
<html>
<head>
    <title>Cek Kategori Usia Mahasiswa</title>
</head>
<body>

<h2>Cek Kategori Usia Mahasiswa</h2>

<form method="POST">
    <label>Nama :</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Umur :</label><br>
    <input type="number" name="umur" required><br><br>

    <button type="submit" name="submit">Cek Kategori</button>
</form>

<?php
if (isset($_POST['submit'])) {

    $nama = $_POST['nama'];
    $umur = (int) $_POST['umur'];

    if ($umur < 13) {
        $kategori = "Anak-anak";
    } elseif ($umur <= 17) {
        $kategori = "Remaja";
    } elseif ($umur <= 59) {
        $kategori = "Dewasa";
    } else {
        $kategori = "Lansia";
    }

    echo "<hr>";
    echo "<h3>Hasil</h3>";
    echo "<p>Nama : $nama</p>";
    echo "<p>Umur : $umur tahun</p>";
    echo "<p>Kategori Usia : <b>$kategori</b></p>";
}
?>

</body>
</html>