<?php
session_start();
include('../app/config/database.php');

// Cek akses: Hanya pemilik
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pemilik') {
    header("Location: index.php");
    exit;
}

// ================= MONITORING MOBIL =================
$totalMobil = $conn->query("
    SELECT COUNT(*) AS count 
    FROM mobil
")->fetch_assoc()['count'] ?? 0;

$mobilTersedia = $conn->query("
    SELECT COUNT(*) AS count 
    FROM mobil 
    WHERE status='Tersedia'
")->fetch_assoc()['count'] ?? 0;

$mobilDisewa = $conn->query("
    SELECT COUNT(*) AS count 
    FROM mobil 
    WHERE status!='Tersedia'
")->fetch_assoc()['count'] ?? 0;

$mobilSering = $conn->query("
    SELECT m.nama_mobil AS nama, COUNT(t.id) AS count 
    FROM mobil m 
    LEFT JOIN transaksi t ON m.id = t.mobil_id AND t.status='Selesai'
    GROUP BY m.id 
    ORDER BY count DESC 
    LIMIT 1
")->fetch_assoc();

$mobilJarang = $conn->query("
    SELECT m.nama_mobil AS nama, COUNT(t.id) AS count 
    FROM mobil m 
    LEFT JOIN transaksi t ON m.id = t.mobil_id AND t.status='Selesai'
    GROUP BY m.id 
    ORDER BY count ASC 
    LIMIT 1
")->fetch_assoc();

// LIST MOBIL UNTUK GRID
$listMobil = $conn->query("
    SELECT 
        m.id,
        m.nama_mobil,
        m.gambar_mobil,
        m.status,

        COUNT(t.id) AS total_disewa,
        COALESCE(SUM(t.total_harga), 0) AS total_pendapatan,
        MAX(t.tanggal_mulai) AS terakhir_disewa

    FROM mobil m
    LEFT JOIN transaksi t 
        ON m.id = t.mobil_id 
        AND t.status = 'Selesai'

    GROUP BY m.id
    ORDER BY total_disewa DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Monitoring | Simpati Trans</title>
  <link rel="stylesheet" href="css/monitoring_mobil.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="../img/logo2.png" alt="Logo Simpati Trans">
            </div>
        </div>

        <ul class="menu">
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li class="active"><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="perawatan.php"><i class="fa-solid fa-screwdriver-wrench"></i> Perawatan Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li class="active"><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
              <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            <?php endif; ?>

          </ul>
    </aside>

    <div class="monitoring-summary">

    <div class="options">
        <a href="monitoring.php" class="option-btn">Aktivitas</a>
        <a href="monitoring_mobil.php" class="option-btn active">Mobil</a>
     </div>

    <div class="summary-card">
        <h4>Total Mobil</h4>
        <p><?= $totalMobil; ?></p>
    </div>
    <div class="summary-card">
        <h4>Mobil Tersedia</h4>
        <p><?= $mobilTersedia; ?></p>
    </div>
    <div class="summary-card">
        <h4>Mobil Disewa</h4>
        <p><?= $mobilDisewa; ?></p>
    </div>
    <div class="summary-card highlight">
        <h4>Paling Laku</h4>
        <p><?= $mobilSering['nama'] ?? 'N/A'; ?></p>
        <small><?= $mobilSering['count'] ?? 0; ?> kali</small>
    </div>
    <div class="summary-card warning">
        <h4>Jarang Disewa</h4>
        <p><?= $mobilJarang['nama'] ?? 'N/A'; ?></p>
        <small><?= $mobilJarang['count'] ?? 0; ?> kali</small>
    </div>
</div>

<!-- GRID MOBIL -->
<div class="mobil-grid">
<?php while ($m = $listMobil->fetch_assoc()): ?>

<?php
$total = (int)$m['total_disewa'];

if ($total >= 10) {
    $performa = "Sangat Laku 🔥";
    $classPerforma = "hot";
} elseif ($total >= 5) {
    $performa = "Normal 👍";
    $classPerforma = "normal";
} else {
    $performa = "Sepi ⚠️";
    $classPerforma = "low";
}
?>

<div class="mobil-card">
    <div class="mobil-image">
        <img src="../img/<?= htmlspecialchars($m['gambar_mobil']); ?>" 
             alt="<?= htmlspecialchars($m['nama_mobil']); ?>">
    </div>

    <div class="mobil-body">
        <h3><?= htmlspecialchars($m['nama_mobil']); ?></h3>

        <span class="status <?= ($m['status']=='Tersedia') ? 'available' : 'busy'; ?>">
            Status: <?= $m['status']; ?>
        </span>

        <p class="monitoring-text">
            Disewa: <strong><?= $m['total_disewa']; ?></strong> kali
        </p>

        <p class="monitoring-text">
            Pendapatan: 
            <strong>Rp <?= number_format($m['total_pendapatan'] ?? 0, 0, ',', '.'); ?></strong>
        </p>

        <p class="monitoring-text">
            Terakhir Disewa: 
            <strong>
                <?= $m['terakhir_disewa'] 
                    ? date('d M Y', strtotime($m['terakhir_disewa'])) 
                    : '-'; ?>
            </strong>
        </p>

        <p class="monitoring-text performa <?= $classPerforma; ?>">
            Performa: <strong><?= $performa; ?></strong>
        </p>

        <?php if ($m['status']=='Tersedia'): ?>
            <button class="btn-ready">Tersedia</button>
        <?php else: ?>
            <button class="btn-busy">Tidak Tersedia</button>
        <?php endif; ?>
    </div>
</div>
<?php endwhile; ?>
</div>

</body>
</html>