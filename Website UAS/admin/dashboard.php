<?php
include '../apps/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Stats
$total_warga = $pdo->query("SELECT COUNT(*) FROM users WHERE role='warga'")->fetchColumn();
$total_belum_bayar = $pdo->query("SELECT COUNT(*) FROM pembayaran WHERE status='belum'")->fetchColumn();
$total_lunas = $pdo->query("SELECT COUNT(*) FROM pembayaran WHERE status='lunas'")->fetchColumn();
$total_pendapatan = $pdo->query("SELECT SUM(total_harga) FROM pembayaran WHERE status='lunas'")->fetchColumn() ?: 0;

$stmt_terbaru = $pdo->query("SELECT p.*, u.nama_lengkap FROM pembayaran p 
                             JOIN users u ON p.user_id = u.id 
                             ORDER BY p.id DESC LIMIT 5");
?>
<?php include '../assets/header.php'; ?>

<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-shield-alt"></i> Admin Panel
    </div>
    <div class="navbar-menu">
        <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="warga.php"><i class="fas fa-users"></i> Warga</a>
        <a href="pembayaran.php"><i class="fas fa-file-invoice-dollar"></i> Pembayaran</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>

<div class="container">
    <div class="welcome-section">
        <div class="greeting">Selamat Datang di Panel Admin,</div>
        <div class="user-name"><span><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <h3><?= $total_warga ?></h3>
            <p>Total Warga</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <h3><?= $total_belum_bayar ?></h3>
            <p>Tagihan Tertunda</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <h3><?= $total_lunas ?></h3>
            <p>Pembayaran Berhasil</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-coins"></i></div>
            <h3>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
            <p>Total Pendapatan</p>
        </div>
    </div>
    
    <div class="card animate-delay-2">
        <div class="card-header">
            <i class="fas fa-history"></i> Transaksi Terbaru
        </div>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Warga</th>
                        <th>Periode</th>
                        <th>Volume</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 0;
                    while($row = $stmt_terbaru->fetch()): 
                        $count++;
                    ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong>
                        </td>
                        <td><?= $row['bulan'] ?> <?= $row['tahun'] ?></td>
                        <td><?= $row['jumlah_air'] ?> m³</td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($row['status'] == 'lunas'): ?>
                                <span class="badge badge-success"><i class="fas fa-check"></i> Lunas</span>
                            <?php else: ?>
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if ($count == 0): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada data transaksi</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../assets/footer.php'; ?>