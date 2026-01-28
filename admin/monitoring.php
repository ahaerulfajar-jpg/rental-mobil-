<?php
session_start();
include('../app/config/database.php');

// Cek akses: Hanya pemilik
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pemilik') {
    header("Location: index.php");
    exit;
}

// ================= TOTAL PENDAPATAN =================
$totalPendapatan = $conn->query("
    SELECT IFNULL(SUM(total_harga),0) AS total 
    FROM transaksi 
    WHERE status='Selesai'
")->fetch_assoc()['total'] ?? 0;


// ================= TRANSAKSI TERBESAR =================
$transaksiTerbesar = $conn->query("
    SELECT MAX(total_harga) AS max 
    FROM transaksi 
    WHERE status='Selesai'
")->fetch_assoc()['max'] ?? 0;


// ================= TRANSAKSI GAGAL =================
// (di database hanya ada 'Ditolak')
$transaksiGagal = $conn->query("
    SELECT COUNT(*) AS count 
    FROM transaksi 
    WHERE status='Ditolak'
")->fetch_assoc()['count'] ?? 0;


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


// ================= MONITORING PELANGGAN =================
$totalPelanggan = $conn->query("
    SELECT COUNT(*) AS count 
    FROM pelanggan
")->fetch_assoc()['count'] ?? 0;

$pelangganAktif = $conn->query("
    SELECT COUNT(DISTINCT pelanggan_id) AS count 
    FROM transaksi 
    WHERE tanggal_mulai >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
")->fetch_assoc()['count'] ?? 0;

$pelangganSering = $conn->query("
    SELECT p.nama, COUNT(t.id) AS count 
    FROM pelanggan p
    JOIN transaksi t ON p.id = t.pelanggan_id
    WHERE t.status='Selesai'
    GROUP BY p.id 
    ORDER BY count DESC 
    LIMIT 1
")->fetch_assoc();

$riwayatPelanggan = $conn->query("
    SELECT p.nama, t.tanggal_mulai, t.total_harga 
    FROM transaksi t
    JOIN pelanggan p ON t.pelanggan_id = p.id
    WHERE t.status='Selesai'
    ORDER BY t.tanggal_mulai DESC 
    LIMIT 10
");


// ================= MONITORING SOPIR =================
$totalSopirAktif = $conn->query("
    SELECT COUNT(*) AS count 
    FROM sopir 
    WHERE status='Aktif'
")->fetch_assoc()['count'] ?? 0;

$sopirSering = $conn->query("
    SELECT s.nama, COUNT(t.id) AS count 
    FROM sopir s
    LEFT JOIN transaksi t ON s.id = t.sopir_id AND t.status='Selesai'
    GROUP BY s.id 
    ORDER BY count DESC 
    LIMIT 1
")->fetch_assoc();

$sopirJarang = $conn->query("
    SELECT s.nama, COUNT(t.id) AS count 
    FROM sopir s
    LEFT JOIN transaksi t ON s.id = t.sopir_id AND t.status='Selesai'
    GROUP BY s.id 
    ORDER BY count ASC 
    LIMIT 1
")->fetch_assoc();

$statusSopir = $conn->query("
    SELECT nama, status 
    FROM sopir
");


// ================= MONITORING TRANSAKSI =================
$totalTransaksi = $conn->query("
    SELECT COUNT(*) AS count 
    FROM transaksi
")->fetch_assoc()['count'] ?? 0;

$transaksiSelesai = $conn->query("
    SELECT COUNT(*) AS count 
    FROM transaksi 
    WHERE status='Selesai'
")->fetch_assoc()['count'] ?? 0;

$transaksiMenunggu = $conn->query("
    SELECT COUNT(*) AS count 
    FROM transaksi 
    WHERE status='Menunggu'
")->fetch_assoc()['count'] ?? 0;

$transaksiDitolak = $conn->query("
    SELECT COUNT(*) AS count 
    FROM transaksi 
    WHERE status='Ditolak'
")->fetch_assoc()['count'] ?? 0;


// ================= GRAFIK TRANSAKSI BULANAN =================
$grafikData = [];
for ($i = 1; $i <= 12; $i++) {
    $count = $conn->query("
        SELECT COUNT(*) AS count 
        FROM transaksi 
        WHERE MONTH(tanggal_mulai)='$i' 
        AND YEAR(tanggal_mulai)=YEAR(CURDATE()) 
        AND status='Selesai'
    ")->fetch_assoc()['count'] ?? 0;

    $grafikData[] = $count;
}

$query = "
    SELECT 
        id,
        nama_pelanggan,
        tanggal_mulai,
        total_harga,
        status
    FROM transaksi
    ORDER BY created_at DESC
";
$riwayatPelanggan = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Monitoring | Simpati Trans</title>
  <link rel="stylesheet" href="css/monitoring.css">
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

    <main class="main-content">
        <!-- Tambahan: Opsi Filter -->
        <div class="options">
            <a href="datamobil.php" class="option-btn active">Aktivitas</a>
            <a href="monitoring_mobil.php" class="option-btn">Mobil</a>
        </div>

        <!-- Indikator Utama -->
        <div class="stats">
            <div class="card">
                <i class="fa-solid fa-money-bill-wave"></i>
                <h3>Total Pendapatan</h3>
                <p>Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></p>
            </div>
            <div class="card">
                <i class="fa-solid fa-receipt"></i>
                <h3>Transaksi Terbesar</h3>
                <p>Rp <?= number_format($transaksiTerbesar, 0, ',', '.'); ?></p>
            </div>
            <div class="card">
                <i class="fa-solid fa-times-circle"></i>
                <h3>Transaksi Gagal/Dibatalkan</h3>
                <p><?= $transaksiGagal; ?></p>
            </div>
        </div>

            <!-- Monitoring Pelanggan -->
            <div class="info-box">
                <h3>Monitoring Pelanggan</h3>
                <ul>
                    <li>Total Pelanggan: <?= $totalPelanggan; ?></li>
                    <li>Pelanggan Aktif (30 hari): <?= $pelangganAktif; ?></li>
                    <li>Pelanggan Paling Sering Menyewa: <?= $pelangganSering['username'] ?? 'N/A'; ?> (<?= $pelangganSering['count'] ?? 0; ?> kali)</li>
                </ul>
                <h4>Riwayat Transaksi Pelanggan (Terbaru)</h4>
                <table>
                    <thead>
                        <tr><th>Pelanggan</th><th>Tanggal</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $riwayatPelanggan->fetch_assoc()): ?>
                        <tr>
                           <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-'); ?></td>
                            <td>
                                <?= !empty($row['tanggal_mulai'])
                                    ? date('d-m-Y', strtotime($row['tanggal_mulai']))
                                    : '-' ?>
                            </td>>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Monitoring Sopir -->
            <div class="info-box">
                <h3>Monitoring Sopir</h3>
                <ul>
                    <li>Total Sopir Aktif: <?= $totalSopirAktif; ?></li>
                    <li>Sopir Sering Bertugas: <?= $sopirSering['nama'] ?? 'N/A'; ?> (<?= $sopirSering['count'] ?? 0; ?> kali)</li>
                    <li>Sopir Jarang Dipakai: <?= $sopirJarang['nama'] ?? 'N/A'; ?> (<?= $sopirJarang['count'] ?? 0; ?> kali)</li>
                </ul>
                <h4>Status Sopir</h4>
                <ul>
                    <?php while ($row = $statusSopir->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['nama']); ?> - <?= $row['status']; ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </main>
</div>

</body>
</html>