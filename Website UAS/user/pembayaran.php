<?php
include '../apps/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warga') {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Process payment if requested
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'bayar') {
    $pembayaran_id = $_POST['pembayaran_id'];
    $metode = $_POST['metode_pembayaran'];
    
    $bukti_nama = null;
    // Handle file upload
    if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['bukti_pembayaran']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $bukti_nama = 'bukti_' . time() . '_' . $pembayaran_id . '.' . $ext;
            $upload_path = '../assets/uploads/' . $bukti_nama;
            move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $upload_path);
        }
    }
    
    // Status jadi menunggu, simpan bukti dan metode
    $stmt = $pdo->prepare("UPDATE pembayaran SET status='menunggu', metode_pembayaran=?, bukti_pembayaran=? WHERE id=? AND user_id=?");
    $stmt->execute([$metode, $bukti_nama, $pembayaran_id, $user_id]);
    
    $success = "Bukti pembayaran berhasil diunggah! Menunggu konfirmasi admin.";
}

// Use prepared statement
$stmt = $pdo->prepare("SELECT * FROM pembayaran WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$user_id]);
$pembayaran = $stmt;
?>
<?php include '../assets/header.php'; ?>

<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-tint"></i> Dusun Topengan
    </div>
    <div class="navbar-menu">
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="pembayaran.php" class="active"><i class="fas fa-file-invoice-dollar"></i> Tagihan</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>

<div class="container">
    <div class="page-header">
        <div>
            <h2 class="page-title">Tagihan Saya</h2>
            <p class="page-subtitle">Kelola dan bayar tagihan air Anda</p>
        </div>
    </div>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success animate-fade-in-up"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger animate-fade-in-up"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="card table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Bulan/Tahun</th>
                    <th>Pemakaian (m³)</th>
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
                    <td>
                        <strong><?= $row['bulan'] ?></strong>
                        <div class="text-secondary text-sm mt-1"><?= $row['tahun'] ?></div>
                    </td>
                    <td><?= $row['jumlah_air'] ?></td>
                    <td>
                        <strong>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></strong>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'lunas'): ?>
                            <span class="badge badge-success"><i class="fas fa-check"></i> Lunas</span>
                        <?php elseif ($row['status'] == 'menunggu'): ?>
                            <span class="badge badge-info"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi</span>
                        <?php else: ?>
                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $row['tanggal_bayar'] ? date('d M Y', strtotime($row['tanggal_bayar'])) : '-' ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'belum'): ?>
                            <button class="btn btn-primary btn-sm" onclick="showBayarModal(<?= $row['id'] ?>, '<?= $row['bulan'] ?> <?= $row['tahun'] ?>', <?= $row['total_harga'] ?>)">
                                <i class="fas fa-wallet"></i> Bayar
                            </button>
                        <?php else: ?>
                            <button class="btn btn-outline btn-sm" disabled>
                                <i class="fas fa-check-double"></i> Selesai
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <?php if ($count == 0): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-file-invoice"></i>
                                <p>Belum ada riwayat tagihan</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Pembayaran Warga -->
<div id="bayarModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-title">
            <i class="fas fa-wallet"></i> Konfirmasi Pembayaran
        </div>
        
        <div class="payment-detail">
            <div class="detail-row">
                <span class="label">Periode Tagihan</span>
                <span class="value" id="modalPeriode">-</span>
            </div>
            <div class="detail-row">
                <span class="label">Total Pembayaran</span>
                <span class="value" id="modalTotal">-</span>
            </div>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="bayar">
            <input type="hidden" name="pembayaran_id" id="modalPembayaranId">
            <input type="hidden" id="modalTotalRaw">
            
            <div class="form-group mt-4">
                <label>Pilih Metode Pembayaran</label>
                <div class="input-group">
                    <select name="metode_pembayaran" id="metodePembayaran" class="form-control" required onchange="updatePaymentInfo()">
                        <option value="">-- Silakan Pilih Metode --</option>
                        <optgroup label="E-Wallet">
                            <option value="gopay">GoPay</option>
                            <option value="dana">DANA</option>
                            <option value="ovo">OVO</option>
                        </optgroup>
                        <optgroup label="Transfer Bank">
                            <option value="bca">BCA Virtual Account</option>
                            <option value="mandiri">Mandiri Virtual Account</option>
                            <option value="bri">BRI Virtual Account</option>
                        </optgroup>
                    </select>
                    <i class="fas fa-money-check-alt input-icon"></i>
                </div>
            </div>
            
            <div id="paymentInstructions" class="payment-instructions">
                <!-- Instructions injected via JS -->
            </div>
            
            <div class="form-group mt-4">
                <label>Upload Bukti Pembayaran (JPG/PNG)</label>
                <div class="input-group file-input-group">
                    <input type="file" name="bukti_pembayaran" class="form-control file-input" accept="image/jpeg, image/png" required>
                    <i class="fas fa-image input-icon"></i>
                </div>
            </div>
            
            <div class="modal-actions mt-5">
                <button type="button" class="btn btn-outline" onclick="closeModal('bayarModal')">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" id="btnKonfirmasi" disabled>
                    <i class="fas fa-upload"></i> Upload & Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showBayarModal(id, periode, total) {
        document.getElementById('modalPembayaranId').value = id;
        document.getElementById('modalPeriode').textContent = periode;
        document.getElementById('modalTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('modalTotalRaw').value = total;
        
        // Reset form
        document.getElementById('metodePembayaran').value = '';
        document.getElementById('paymentInstructions').style.display = 'none';
        document.getElementById('btnKonfirmasi').disabled = true;
        
        document.getElementById('bayarModal').classList.add('active');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }
    
    function updatePaymentInfo() {
        const method = document.getElementById('metodePembayaran').value;
        const instructions = document.getElementById('paymentInstructions');
        const btn = document.getElementById('btnKonfirmasi');
        const total = document.getElementById('modalTotalRaw').value;
        
        if (!method) {
            instructions.style.display = 'none';
            btn.disabled = true;
            return;
        }
        
        let info = '';
        if (method === 'gopay') info = `Buka aplikasi Gojek, scan QRIS atau transfer ke nomor GoPay: <strong>0812-3456-7890</strong> a.n. PDAM Topengan.`;
        else if (method === 'dana') info = `Buka aplikasi DANA, transfer ke nomor: <strong>0812-3456-7890</strong> a.n. PDAM Topengan.`;
        else if (method === 'ovo') info = `Buka aplikasi OVO, transfer ke nomor: <strong>0812-3456-7890</strong> a.n. PDAM Topengan.`;
        else if (method === 'bca') info = `Buka BCA Mobile/ATM, bayar Virtual Account ke: <strong>3901-1234-5678-9000</strong> a.n. PDAM Topengan.`;
        else if (method === 'mandiri') info = `Buka Livin'/ATM, bayar Virtual Account ke: <strong>8800-1234-5678-9000</strong> a.n. PDAM Topengan.`;
        else if (method === 'bri') info = `Buka BRImo/ATM, bayar BRIVA ke: <strong>7700-1234-5678-9000</strong> a.n. PDAM Topengan.`;
        
        instructions.innerHTML = `
            <div class="flex-gap-3 mb-3">
                <i class="fas fa-info-circle info-icon-primary mt-1"></i>
                <div>
                    <p class="mb-2 line-height-normal text-primary">${info}</p>
                    <p class="text-secondary">Nominal transfer harus pas: <strong class="text-success text-bold">Rp ${new Intl.NumberFormat('id-ID').format(total)}</strong></p>
                </div>
            </div>
        `;
        instructions.style.display = 'block';
        btn.disabled = false;
    }
</script>

<?php include '../assets/footer.php'; ?>