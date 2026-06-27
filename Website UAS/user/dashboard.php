<?php
include '../apps/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warga') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Secure prepared statements for queries
$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM pembayaran WHERE user_id=?");
$stmt_total->execute([$user_id]);
$total_tagihan = $stmt_total->fetchColumn();

$stmt_belum = $pdo->prepare("SELECT COUNT(*) FROM pembayaran WHERE user_id=? AND status='belum'");
$stmt_belum->execute([$user_id]);
$total_belum = $stmt_belum->fetchColumn();

$stmt_lunas = $pdo->prepare("SELECT COUNT(*) FROM pembayaran WHERE user_id=? AND status='lunas'");
$stmt_lunas->execute([$user_id]);
$total_lunas = $stmt_lunas->fetchColumn();

$stmt_nominal = $pdo->prepare("SELECT SUM(total_harga) FROM pembayaran WHERE user_id=? AND status='belum'");
$stmt_nominal->execute([$user_id]);
$nominal_belum = $stmt_nominal->fetchColumn() ?: 0;
?>
<?php include '../assets/header.php'; ?>

<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-tint"></i> Dusun Topengan
    </div>
    <div class="navbar-menu">
        <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="pembayaran.php"><i class="fas fa-file-invoice-dollar"></i> Tagihan</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>

<div class="container">
    <div class="welcome-section">
        <div class="greeting">Selamat Datang di Portal Warga,</div>
        <div class="user-name"><span><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
            <h3><?= $total_tagihan ?></h3>
            <p>Total Tagihan</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <h3><?= $total_belum ?></h3>
            <p>Belum Dibayar</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <h3><?= $total_lunas ?></h3>
            <p>Tagihan Lunas</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            <h3>Rp <?= number_format($nominal_belum, 0, ',', '.') ?></h3>
            <p>Total Tunggakan</p>
        </div>
    </div>
    
    <div class="info-card">
        <p>
            <i class="fas fa-info-circle info-icon-primary" style="margin-right:8px;"></i>
            <strong>Informasi Tarif:</strong> Harga air per meter kubik (m³) saat ini adalah <strong>Rp 5.000</strong>.
            Pastikan Anda membayar tagihan sebelum tanggal 10 setiap bulannya untuk menghindari pemutusan saluran.
        </p>
    </div>
    
    <div class="card animate-delay-2">
        <div class="card-header">
            <i class="fas fa-bolt"></i> Aksi Cepat
        </div>
        <?php if ($total_belum > 0): ?>
            <p class="mb-4 text-secondary">Anda memiliki <?= $total_belum ?> tagihan yang belum dibayar.</p>
            <a href="pembayaran.php" class="btn btn-primary">
                <i class="fas fa-wallet"></i> Bayar Tagihan Sekarang
            </a>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-check-circle text-success" style="font-size: 56px;"></i>
                <p class="mt-4 text-primary" style="font-size: 16px;">Semua tagihan Anda sudah lunas!</p>
                <p class="text-secondary mt-2">Terima kasih atas partisipasi Anda membangun desa.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../assets/footer.php'; ?>