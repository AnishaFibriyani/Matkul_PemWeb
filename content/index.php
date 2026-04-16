<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemrograman Web</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .header {
            display: flex;
            align-items: center;
            border: 1px solid #999;
            padding: 10px;
        }

        .logo {
            width: 135px;
            margin-right: 15px;
        }

        .title {
            text-align: left; 
        }

        .title h1 {
            margin: 18px;
            font-size: 30px;
            font-weight: normal;
        }

        .title h2 {
            margin: 18px;
            font-size: 20px;
            font-weight: normal;
        }

        .subtitle {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        hr {
            margin: 15px 0;
        }

        h3 {
            margin-top: 20px;
        }

        ul, ol {
            margin-left: 20px;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <img src="foto/logo.jpg" class="logo">

        <div class="title">
            <h1>Selamat Datang di Mata Kuliah Pemrograman Web</h1>
            <h2>Pertemuan 2: HTML Dasar</h2>
        </div>
    </div>

    <div class="subtitle">
        <p>
            Mata kuliah ini memperkenalkan dasar-dasar pemrograman web menggunakan HTML, CSS, dan JavaScript.
        </p>
        <p>
            Pada pertemuan ini, kita fokus pada struktur dan elemen dasar HTML.
        </p>
    </div>

    <hr>

    <p>
        HTML adalah bahasa markup yang digunakan untuk membuat struktur halaman web.
        Dengan HTML, kita bisa menampilkan teks, gambar, dan link secara terorganisir.
        Untuk informasi lebih lanjut dari HTML dapat kunjungi link berikut:
        <a href="#">Penjelasan HTML</a>
    </p>

    <h3>Topik Utama Pertemuan Ini:</h3>
    <ul>
        <li>Struktur HTML</li>
        <li>Heading dan Paragraf</li>
        <li>List (Unordered dan Ordered)</li>
        <li>Link dan Gambar</li>
        <li>Tabel</li>
    </ul>

    <h3>Langkah Praktikum:</h3>
    <ol>
        <li>Buat file index.php</li>
        <li>Tulis kode HTML</li>
        <li>Simpan dan buka di browser</li>
        <ul>
            <li>buka xampp</li>
            <li>nyalakan server</li>
            <li>buka browser dengan url localhost/...</li>
        </ul>
        <li>Verifikasi hasil</li>
    </ol>

</div>

</body>
</html>