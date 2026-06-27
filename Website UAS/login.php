<?php
include 'apps/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama_lengkap'];
        
        if ($user['role'] == 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: user/dashboard.php');
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Pembayaran Air</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="card auth-card">
            <div class="auth-icon" style="font-size: 50px; color: var(--primary); margin-bottom: 10px;">
                <i class="fas fa-tint"></i>
            </div>
            <h2>Login</h2>
            <div class="auth-subtitle" style="color: var(--text-secondary); margin-bottom: 25px;">Dusun Topengan - Sistem Pembayaran Air</div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger animate-fade-in-up"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group animate-fade-in-up animate-delay-1">
                    <label>Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                <div class="form-group animate-fade-in-up animate-delay-2">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block animate-fade-in-up animate-delay-3" style="margin-top: 15px;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <div class="auth-link animate-fade-in-up animate-delay-4">
                Belum punya akun? <a href="register.php">Daftar sekarang</a>
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