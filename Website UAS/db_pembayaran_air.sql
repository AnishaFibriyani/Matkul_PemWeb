-- ============================================
-- Database: db_pembayaran_air
-- Sistem Pembayaran Air Dusun Topengan
-- ============================================

CREATE DATABASE IF NOT EXISTS db_pembayaran_air;
USE db_pembayaran_air;

-- Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    no_telepon VARCHAR(20),
    alamat TEXT,
    role ENUM('admin', 'warga') DEFAULT 'warga',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Pembayaran
CREATE TABLE IF NOT EXISTS pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bulan VARCHAR(20) NOT NULL,
    tahun INT NOT NULL,
    jumlah_air DECIMAL(10,2) NOT NULL,
    total_harga DECIMAL(12,0) NOT NULL,
    status ENUM('belum', 'lunas') DEFAULT 'belum',
    tanggal_bayar DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Admin Default (password: admin123)
INSERT INTO users (username, password, nama_lengkap, role) 
VALUES ('admin', MD5('admin123'), 'Administrator', 'admin')
ON DUPLICATE KEY UPDATE username = username;
