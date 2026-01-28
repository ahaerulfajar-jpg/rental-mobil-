<?php
session_start();
include('../app/config/database.php'); 

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php"); 
    exit;
}

$username = $_SESSION['username'];

// Ambil data admin
$stmt = $conn->prepare("
    SELECT id, username, email, role, created_at
    FROM users
    WHERE username = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();

$admin = $stmt->get_result()->fetch_assoc();

// Proteksi jika data tidak ditemukan
if (!$admin) {
    die("Data admin tidak ditemukan.");
}

$admin_id = $admin['id'];

// Total Login
$qLogin = $conn->query("
    SELECT COUNT(*) total 
    FROM log_aktivitas 
    WHERE admin_id = $admin_id AND jenis='login'
");
$totalLogin = $qLogin->fetch_assoc()['total'];

// Total Update
$qUpdate = $conn->query("
    SELECT COUNT(*) total 
    FROM log_aktivitas 
    WHERE admin_id = $admin_id AND jenis='update'
");
$totalUpdate = $qUpdate->fetch_assoc()['total'];

// Login Terakhir
$qLast = $conn->query("
    SELECT created_at 
    FROM log_aktivitas 
    WHERE admin_id = $admin_id AND jenis='login'
    ORDER BY created_at DESC LIMIT 1
");
$lastLogin = $qLast->fetch_assoc()['created_at'] ?? '-';

$qAktivitas = $conn->query("
    SELECT aktivitas, created_at 
    FROM log_aktivitas 
    WHERE admin_id = $admin_id 
    ORDER BY created_at DESC 
    LIMIT 5
");

// Ambil riwayat aktivitas admin
$stmtAktivitas = $conn->prepare("
    SELECT aktivitas, created_at
    FROM log_aktivitas
    WHERE admin_id = ?
    ORDER BY created_at DESC
    LIMIT 10
");
$stmtAktivitas->bind_param("i", $admin_id);
$stmtAktivitas->execute();
$aktivitas = $stmtAktivitas->get_result();

$conn->query("
    INSERT INTO log_aktivitas (admin_id, aktivitas, jenis)
    VALUES ($admin_id, 'Login ke sistem', 'login')
");

$conn->query("
    INSERT INTO log_aktivitas (admin_id, aktivitas, jenis)
    VALUES ($admin_id, 'Mengubah data mobil', 'update')
");

$conn->query("
    INSERT INTO log_aktivitas (admin_id, aktivitas, jenis)
    VALUES ($admin_id, 'Mengubah password', 'password')
");

// Transaksi
$qTransaksi = $conn->query("SELECT COUNT(*) AS total FROM transaksi");
$totalTransaksi = $qTransaksi->fetch_assoc()['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile Admin</title>
    <link rel="stylesheet" href="css/profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
     <aside class="sidebar">
        <div class="sidebar-header">
    <div class="logo">
        <img src="../img/logo2.png" alt="Logo Simpati Trans">
    </div>
</div>

<ul class="menu">
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
            <li class="active"><a href="profil.php"><i class="fa-solid fa-person"></i> Profil</a></li>
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li class="active"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
            <?php endif; ?>
            
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          </ul>
 </aside>

<main class="main-content">

    <!-- HEADER PROFILE -->
    <section class="profile-header">
        <div class="profile-avatar">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div class="profile-info">
            <h2><?= htmlspecialchars($admin['username']); ?></h2>
            <p><?= htmlspecialchars($admin['email']); ?></p>
            <span class="role"><?= ucfirst($admin['role']); ?></span>
        </div>
    </section>

    <!-- STATISTIK AKTIVITAS -->
    <section class="stats-section">
        <h3>Statistik Aktivitas</h3>

        <div class="stats-grid">
            <div class="stat-card">
                <h4>Login</h4>
                <p><?= $totalLogin; ?></p>
                <span>Kali</span>
            </div>

            <div class="stat-card">
                <h4>Transaksi</h4>
                <p><?= $totalTransaksi; ?></p>
                <span>Data</span>
            </div>

            <div class="stat-card">
                <h4>Update Data</h4>
                <p><?= $totalUpdate; ?></p>
                <span>Aksi</span>
            </div>
        </div>
    </section>

    <!-- RIWAYAT AKTIVITAS -->
    <section class="activity-section">
       <!-- Container dengan scroll -->
    <div class="activity-list-container">
        <!-- Header opsional -->
        <div class="activity-list-header">
            <h4>Log Aktivitas</h4>
            <div class="activity-actions">
                <button class="filter-btn">Filter</button>
                <button class="refresh-btn">Refresh</button>
            </div>
        </div>
        
        <!-- Daftar aktivitas -->
        <ul class="activity-list">
            <?php if($aktivitas->num_rows > 0): ?>
                <?php while($row = $aktivitas->fetch_assoc()): ?>
                    <li>
                        <i class="fa-solid fa-clock"></i>
                        <?= htmlspecialchars($row['aktivitas']); ?>
                        <span><?= date('d M Y H:i', strtotime($row['created_at'])); ?></span>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="empty-message">Belum ada riwayat aktivitas</li>
            <?php endif; ?>
        </ul>
    </div>
</section>

    <!-- KEAMANAN AKUN -->
    <section class="security-section">
        <h3>Keamanan Akun</h3>

        <div class="security-card">
            <div class="security-item">
                <span>Password</span>
                <button onclick="openOverlay()">Ubah Username / Password</button>
            </div>

         <div class="security-item">
            <span>Login Terakhir</span>
            <strong>
                <?= $lastLogin != '-' ? date('d M Y H:i', strtotime($lastLogin)) : '-'; ?>
            </strong>
        </div>

        <div class="security-item">
            <span>Status Akun</span>
            <strong class="active">Aktif</strong>
        </div>
            
        </div>
    </section>
</main>

<!-- OVERLAY UBAH AKUN -->
<div id="overlayAccount" class="overlay">
    <div class="overlay-card">
        <h3>Ubah Akun Admin</h3>

        <form method="POST" action="../app/admin/proses_akun.php">
            <input type="hidden" name="admin_id" value="<?= $admin_id; ?>">

            <div class="form-group">
                <label>Username Baru</label>
                <input type="text" name="username" value="<?= htmlspecialchars($admin['username']); ?>" required>
            </div>

            <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="password_lama" required>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password_baru">
                <small>*Kosongkan jika tidak ingin mengubah password</small>
            </div>

            <div class="overlay-actions">
                <button type="submit" class="btn-save">Simpan</button>
                <button type="button" class="btn-cancel" onclick="closeOverlay()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script src ="js/dataadmin.js"></script>
</body>
</html>