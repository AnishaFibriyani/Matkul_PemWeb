<?php
session_start();

$host = 'localhost';
$dbname = 'db_pembayaran_air';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Auto-migrate database for new features
    try {
        $pdo->query("SELECT bukti_pembayaran FROM pembayaran LIMIT 1");
    } catch(PDOException $e) {
        $pdo->exec("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('belum', 'menunggu', 'lunas') DEFAULT 'belum'");
        $pdo->exec("ALTER TABLE pembayaran ADD COLUMN bukti_pembayaran VARCHAR(255) NULL AFTER status");
        $pdo->exec("ALTER TABLE pembayaran ADD COLUMN metode_pembayaran VARCHAR(50) NULL AFTER jumlah_air");
    }
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>