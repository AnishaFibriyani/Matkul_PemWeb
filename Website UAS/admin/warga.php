<?php
include '../apps/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// CRUD Warga
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $nama = $_POST['nama_lengkap'];
        $telepon = $_POST['no_telepon'];
        $alamat = $_POST['alamat'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, no_telepon, alamat, role) 
                                   VALUES (?, ?, ?, ?, ?, 'warga')");
            $stmt->execute([$username, $password, $nama, $telepon, $alamat]);
            $success = "Warga berhasil ditambahkan!";
        } catch(PDOException $e) {
            $error = "Gagal: Username mungkin sudah digunakan.";
        }
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $nama = $_POST['nama_lengkap'];
        $telepon = $_POST['no_telepon'];
        $alamat = $_POST['alamat'];
        
        $stmt = $pdo->prepare("UPDATE users SET nama_lengkap=?, no_telepon=?, alamat=? WHERE id=?");
        $stmt->execute([$nama, $telepon, $alamat, $id]);
        $success = "Data warga berhasil diperbarui!";
    } elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
        $success = "Warga berhasil dihapus!";
    }
}

$warga = $pdo->query("SELECT * FROM users WHERE role='warga' ORDER BY id DESC");
?>
<?php include '../assets/header.php'; ?>

<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-shield-alt"></i> Admin Panel
    </div>
    <div class="navbar-menu">
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="warga.php" class="active"><i class="fas fa-users"></i> Warga</a>
        <a href="pembayaran.php"><i class="fas fa-file-invoice-dollar"></i> Pembayaran</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>

<div class="container">
    <div class="page-header">
        <div>
            <h2 class="page-title">Data Warga</h2>
            <p class="page-subtitle">Kelola informasi pelanggan air Dusun Topengan</p>
        </div>
        <button class="btn btn-primary" onclick="showModal('addModal')">
            <i class="fas fa-user-plus"></i> Tambah Warga
        </button>
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
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 0;
                while($row = $warga->fetch()): 
                    $count++;
                ?>
                <tr>
                    <td>
                        <span class="badge badge-info"><i class="fas fa-user"></i> <?= htmlspecialchars($row['username']) ?></span>
                    </td>
                    <td><strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong></td>
                    <td><?= htmlspecialchars($row['no_telepon']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?: '-' ?></td>
                    <td>
                        <div class="action-btns flex-gap-2">
                            <button class="btn btn-warning btn-sm" onclick="editWarga(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['nama_lengkap'])) ?>', '<?= htmlspecialchars(addslashes($row['no_telepon'])) ?>', '<?= htmlspecialchars(str_replace(["\r", "\n"], [" ", " "], addslashes($row['alamat']))) ?>')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" onsubmit="return confirm('Yakin ingin menghapus warga ini beserta seluruh data tagihannya?');">
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
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <p>Belum ada data warga yang terdaftar</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Warga -->
<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-title">
            <i class="fas fa-user-plus"></i> Pendaftaran Warga Baru
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            
            <div class="grid-2">
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" required placeholder="Buat username">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" required placeholder="Buat password">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <div class="input-group">
                    <input type="text" name="nama_lengkap" class="form-control" required placeholder="Nama sesuai KTP">
                    <i class="fas fa-id-card input-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label>No Telepon</label>
                <div class="input-group">
                    <input type="text" name="no_telepon" class="form-control" placeholder="Contoh: 081234567890">
                    <i class="fas fa-phone input-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2" placeholder="Nama jalan, RT/RW..."></textarea>
            </div>
            
            <div class="modal-actions mt-5">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Warga -->
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-title">
            <i class="fas fa-user-edit"></i> Update Data Warga
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <div class="input-group">
                    <input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required>
                    <i class="fas fa-id-card input-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label>No Telepon</label>
                <div class="input-group">
                    <input type="text" name="no_telepon" id="edit_telepon" class="form-control">
                    <i class="fas fa-phone input-icon"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" id="edit_alamat" class="form-control" rows="2"></textarea>
            </div>
            
            <div class="modal-actions mt-5">
                <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Update Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showModal(id) {
        document.getElementById(id).classList.add('active');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
    function editWarga(id, nama, telepon, alamat) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_telepon').value = telepon;
        document.getElementById('edit_alamat').value = alamat;
        showModal('editModal');
    }
</script>

<?php include '../assets/footer.php'; ?>