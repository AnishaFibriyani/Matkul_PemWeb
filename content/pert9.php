<h2>PHP Hello World</h2>

<?php

// Sintaks dasar: output sederhana
echo "<p>Hello World! Selamat datang di dunia PHP.</p>";

?>

<hr>

<?php

// Variabel dan kondisi
date_default_timezone_set('Asia/Jakarta');

$time = date('H:i');
$jam = date('H');
$nama = "Mahasiswa";

if ($jam < 12) {
    $salam = "Selamat pagi";
} elseif ($jam < 15) {
    $salam = "Selamat siang";
} elseif ($jam < 18) {
    $salam = "Selamat sore";
} else {
    $salam = "Selamat malam";
}

echo "<p>$salam, $nama! Sekarang jam $time.</p>";

?>

<p>Kode ini menunjukkan bagaimana PHP membuat web dinamis berdasarkan waktu dan logika.</p>

<hr>

<h3>Contoh Operator</h3>

<?php

$a = 10;
$b = 5;

$hasil1 = $a + $b;
$hasil2 = $a - $b;
$hasil3 = $a * $b;
$hasil4 = $a / $b;

echo "<p>Hasil $a + $b = $hasil1</p>";
echo "<p>Hasil $a - $b = $hasil2</p>";
echo "<p>Hasil $a * $b = $hasil3</p>";
echo "<p>Hasil $a / $b = $hasil4</p>";

if ($hasil1 > 10) {
    echo "<p>Hasil penjumlahan lebih besar dari 10.</p>";
} else {
    echo "<p>Hasil penjumlahan tidak lebih besar dari 10.</p>";
}

if ($hasil2 > 10) {
    echo "<p>Hasil pengurangan lebih besar dari 10.</p>";
} else {
    echo "<p>Hasil pengurangan tidak lebih besar dari 10.</p>";
}

?>

<hr>

<form method="POST">

    <input type="number"
           placeholder="Masukkan umur Anda"
           name="umur"
           required>

    <button type="submit" name="submit">
        Cek Status
    </button>

</form>

<?php

if (isset($_POST['submit'])) {

    $age = (int) $_POST['umur'];

    if ($age >= 18) {

        echo "<p>Umur Anda $age tahun. Anda sudah cukup umur!</p>";

    } else {

        echo "<p>Umur Anda $age tahun. Anda belum cukup umur.</p>";

    }
}

?>