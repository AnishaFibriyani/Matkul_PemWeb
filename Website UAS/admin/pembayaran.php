<?php
include '../apps/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// CRUD Pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $user_id = $_POST['user_id'];
        $bulan = $_POST['bulan'];
        $tahun = $_POST['tahun'];
        $jumlah_air = $_POST['jumlah_air'];
        $total_harga = $jumlah_air * 5000; // Harga per m³
        
        $stmt = $pdo->prepare("INSERT INTO pembayaran (user_id, bulan, tahun, jumlah_air, total_harga, status) 
                               VALUES (?, ?, ?, ?, ?, 'belum')");
        $stmt->execute([$user_id, $bulan, $tahun, $jumlah_air, $total_harga]);
        $success = "Tagihan berhasil ditambahkan!";
    } elseif ($_POST['action'] == 'bayar') {
        $id = $_POST['id'];
        $tanggal = date('Y-m-d');
        
        $stmt = $pdo->prepare("UPDATE pembayaran SET status='lunas', tanggal_bayar=? WHERE id=?");
        $stmt->execute([$tanggal, $id]);
        $success = "Pembayaran diverifikasi lunas!";
    } elseif ($_POST['action'] == 'tolak') {
        $id = $_POST['id'];
        // Hapus file bukti_pembayaran jika ada
        $stmt_img = $pdo->prepare("SELECT bukti_pembayaran FROM pembayaran WHERE id=?");
        $stmt_img->execute([$id]);
        $row_img = $stmt_img->fetch();
        if ($row_img && $row_img['bukti_pembayaran']) {
            $file_path = '../assets/uploads/' . $row_img['bukti_pembayaran'];
            if (file_exists($file_path)) unlink($file_path);
        }
        
        $stmt = $pdo->prepare("UPDATE pembayaran SET status='belum', bukti_pembayaran=NULL, metode_pembayaran=NULL WHERE id=?");
        $stmt->execute([$id]);
        $success = "Pembayaran ditolak, tagihan dikembalikan ke status belum bayar.";
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        
        // Hapus file bukti_pembayaran jika ada
        $stmt_img = $pdo->prepare("SELECT bukti_pembayaran FROM pembayaran WHERE id=?");
        $stmt_img->execute([$id]);
        $row_img = $stmt_img->fetch();
        if ($row_img && $row_img['bukti_pembayaran']) {
            $file_path = '../assets/uploads/' . $row_img['bukti_pembayaran'];
            if (file_exists($file_path)) unlink($file_path);
        }
        
        $stmt = $pdo->prepare("DELETE FROM pembayaran WHERE id=?");
        $stmt->execute([$id]);
        $success = "Tagihan berhasil dihapus!";
    }
}

$pembayaran = $pdo->query("SELECT p.*, u.nama_lengkap FROM pembayaran p 
                           JOIN users u ON p.user_id = u.id 
                           ORDER BY p.id DESC");
$warga = $pdo->query("SELECT id, nama_lengkap FROM users WHERE role='warga' ORDER BY nama_lengkap");
?>
<?php include '../assets/header.php'; ?>

<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-shield-alt"></i> Admin Panel
    </div>
    <div class="navbar-menu">
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="warga.php"><i class="fas fa-users"></i> Warga</a>
        <a href="pembayaran.php" class="active"><i class="fas fa-file-invoice-dollar"></i> Pembayaran</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>

<div class="container">
    <div class="page-header">
        <div>
            <h2 class="page-title">Kelola Pembayaran</h2>
            <p class="page-subtitle">Manajemen tagihan dan riwayat pembayaran warga</p>
        </div>
        <button class="btn btn-primary" onclick="showModal('addModal')">
            <i class="fas fa-plus"></i> Buat Tagihan Baru
        </button>
    </div>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success animate-fade-in-up"><?= $success ?></div>
    <?php endif; ?>
    
    <div class="card table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Warga</th>
                    <th>Periode</th>
                    <th>Volume (m³)</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 0;
                while($row = $pembayaran->fetch()): 
                    $count++;
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong></td>
                    <td><?= $row['bulan'] ?> <?= $row['tahun'] ?></td>
                    <td><?= $row['jumlah_air'] ?></td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    <td>
                        <?php if ($row['status'] == 'lunas'): ?>
                            <span class="badge badge-success"><i class="fas fa-check"></i> Lunas</span>
                        <?php elseif ($row['status'] == 'menunggu'): ?>
                            <span class="badge badge-info"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi</span>
                        <?php else: ?>
                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['tanggal_bayar'] ? date('d M Y', strtotime($row['tanggal_bayar'])) : '-' ?></td>
                    <td>
                        <div class="action-btns flex-gap-2">
                            <?php if ($row['status'] == 'menunggu' && $row['bukti_pembayaran']): ?>
                                <button type="button" class="btn btn-info btn-sm" onclick="showProofModal(<?= $row['id'] ?>, '../assets/uploads/<?= htmlspecialchars($row['bukti_pembayaran']) ?>', '<?= htmlspecialchars($row['metode_pembayaran']) ?>')" title="Lihat Bukti">
                                    <i class="fas fa-image"></i> Cek Bukti
                                </button>
                            <?php elseif ($row['status'] == 'belum'): ?>
                                <form method="POST" onsubmit="return confirm('Verifikasi pembayaran ini secara manual?');">
                                    <input type="hidden" name="action" value="bayar">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm" title="Verifikasi Lunas">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <form method="POST" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <?php if ($count == 0): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-file-invoice"></i>
                                <p>Belum ada data tagihan</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Tagihan -->
<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-title">
            <i class="fas fa-plus-circle"></i> Buat Tagihan Baru
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label>Pilih Warga</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Pilih Warga --</option>
                    <?php while($w = $warga->fetch()): ?>
                        <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['nama_lengkap']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label>Bulan</label>
                    <select name="bulan" class="form-control" required>
                        <?php 
                        $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        foreach($months as $m) {
                            $sel = (date('n') == array_search($m, $months) + 1) ? 'selected' : '';
                            echo "<option value='$m' $sel>$m</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tahun</label>
                    <select name="tahun" class="form-control" required>
                        <?php for($i = date('Y'); $i >= 2020; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Jumlah Air (m³)</label>
                <div class="input-group">
                    <input type="number" name="jumlah_air" class="form-control" required min="1" placeholder="Masukkan volume air...">
                    <i class="fas fa-tint input-icon"></i>
                </div>
                <small class="info-text">
                    <i class="fas fa-info-circle"></i> Tarif dasar: Rp 5.000 / m³
                </small>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Tagihan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Bukti Pembayaran -->
<div id="proofModal" class="modal-overlay">
    <div class="modal-content large">
        <div class="modal-title">
            <i class="fas fa-image"></i> Verifikasi Bukti Pembayaran
        </div>
        <div class="mb-3 text-bold">
            Metode: <span id="proofMethod" class="badge badge-info">-</span>
        </div>
        <div class="proof-image-wrapper">
            <img id="proofImage" src="" alt="Bukti Pembayaran">
        </div>
        <div class="modal-actions flex-between">
            <button type="button" class="btn btn-outline" onclick="closeModal('proofModal')">Tutup</button>
            
            <div class="flex-gap-3">
                <form method="POST" onsubmit="return confirm('Tolak pembayaran ini dan minta warga upload ulang?');">
                    <input type="hidden" name="action" value="tolak">
                    <input type="hidden" name="id" id="proofTolakId">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Tolak Bukti</button>
                </form>
                
                <form method="POST" onsubmit="return confirm('Verifikasi pembayaran ini sebagai lunas?');">
                    <input type="hidden" name="action" value="bayar">
                    <input type="hidden" name="id" id="proofBayarId">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Verifikasi Lunas</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showModal(id) {
        document.getElementById(id).classList.add('active');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
    function showProofModal(id, imageUrl, method) {
        document.getElementById('proofTolakId').value = id;
        document.getElementById('proofBayarId').value = id;
        document.getElementById('proofImage').src = imageUrl;
        document.getElementById('proofMethod').textContent = method.toUpperCase();
        showModal('proofModal');
    }
</script>

<?php include '../assets/footer.php'; ?>