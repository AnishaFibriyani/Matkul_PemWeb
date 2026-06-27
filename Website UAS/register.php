<?php
include 'apps/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $nama = $_POST['nama_lengkap'];
    $telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, no_telepon, alamat, role) 
                               VALUES (?, ?, ?, ?, ?, 'warga')");
        $stmt->execute([$username, $password, $nama, $telepon, $alamat]);
        $success = "Pendaftaran berhasil! Silakan login.";
    } catch(PDOException $e) {
        $error = "Username sudah terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Pembayaran Air</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="card auth-card">
            <div class="auth-icon" style="font-size: 50px; color: var(--primary); margin-bottom: 10px;">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>Daftar Akun</h2>
            <div class="auth-subtitle" style="color: var(--text-secondary); margin-bottom: 25px;">Dusun Topengan - Sistem Pembayaran Air</div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success animate-fade-in-up"><?= $success ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger animate-fade-in-up"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group animate-fade-in-up animate-delay-1">
                    <label>Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" placeholder="Pilih username" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                <div class="form-group animate-fade-in-up animate-delay-1">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                <div class="form-group animate-fade-in-up animate-delay-2">
                    <label>Nama Lengkap</label>
                    <div class="input-group">
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama sesuai KTP" required>
                        <i class="fas fa-id-card input-icon"></i>
                    </div>
                </div>
                <div class="form-group animate-fade-in-up animate-delay-2">
                    <label>No Telepon</label>
                    <div class="input-group">
                        <input type="text" name="no_telepon" class="form-control" placeholder="Contoh: 08123456789">
                        <i class="fas fa-phone input-icon"></i>
                    </div>
                </div>
                <div class="form-group animate-fade-in-up animate-delay-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap rumah..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block animate-fade-in-up animate-delay-4" style="margin-top: 15px;">
                    <i class="fas fa-user-check"></i> Daftar
                </button>
            </form>
            <div class="auth-link animate-fade-in-up animate-delay-4">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
    
    <div class="ocean-wrapper" style="position: fixed; bottom: 0; left: 0; width: 100%; height: 100px; z-index: 1;">
        <div class="ocean" style="bottom: 0;">
            <div class="wave"></div>
            <div class="wave"></div>
        </div>
    </div>
</body>
</html>