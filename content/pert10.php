<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pertemuan 10</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            margin:30px;
        }

        .list-utama{
            font-size:18px;
        }
    </style>
</head>
<body>

<h3 style="text-align: center;">Ini adalah halaman untuk pertemuan ke 10</h3>
<hr>

<h3 style="text-align: center;">Array sederhana tentang Buah</h3>

<?php
// Array sederhana
$buah = array("Apel", "Jeruk", "Mangga");
?>

<h3>Daftar Buah:</h3>

<ul class="list-utama">
    <?php foreach ($buah as $item): ?>
        <li><?= $item; ?></li>
    <?php endforeach; ?>
</ul>

<hr>

<?php
// Array data mahasiswa
$mahasiswa = array(
    ["Nama" => "Ali", "NIM" => "12345", "Nilai" => 85],
    ["Nama" => "Budi", "NIM" => "12346", "Nilai" => 90],
    ["Nama" => "Cici", "NIM" => "12347", "Nilai" => 78]
);

echo "<h3>Data Mahasiswa</h3>";
?>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Nilai</th>
            <th>Status</th>
        </tr>

        <?php foreach ($mahasiswa as $mhs): ?>

            <?php
            if ($mhs['Nilai'] >= 80) {
                $status = "Lulus";
            } else {
                $status = "Tidak Lulus";
            }
            ?>

            <tr>
                <td><?= $mhs['Nama']; ?></td>
                <td><?= $mhs['NIM']; ?></td>
                <td><?= $mhs['Nilai']; ?></td>
                <td><?= $status; ?></td>
            </tr>

        <?php endforeach; ?>

    </table>
</div>

<hr>

<?php

$mahasiswa = array(
    ["Nama" => "Ali", "NIM" => "12345", "Nilai" => [85, 90, 88]],
    ["Nama" => "Budi", "NIM" => "12346", "Nilai" => [78, 82, 80]],
    ["Nama" => "Cici", "NIM" => "12347", "Nilai" => [92, 95, 93]]
);

?>

<div class="table-responsive">

<table class="table table-striped table-bordered">

    <thead>
        <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Nilai-1</th>
            <th>Nilai-2</th>
            <th>Nilai-3</th>
            <th>Rata-rata</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach ($mahasiswa as $mhs): ?>

        <tr>

            <td><?= $mhs['Nama']; ?></td>

            <td><?= $mhs['NIM']; ?></td>

            <?php foreach ($mhs['Nilai'] as $nilai): ?>
                <td><?= $nilai; ?></td>
            <?php endforeach; ?>

            <?php
                $rata = array_sum($mhs['Nilai']) / count($mhs['Nilai']);
            ?>

            <td><?= number_format($rata, 2); ?></td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

</div>

</body>
</html>
    

