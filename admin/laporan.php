<?php
session_start();
include('../app/config/database.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pemilik') {
    header("Location: index.php"); // Redirect jika bukan pemilik
    exit;
}

$bulan = date('m');
$tahun = date('Y');

// Query ringkasan harian
$queryTotalTransaksi = "SELECT COUNT(*) AS total FROM transaksi WHERE MONTH(tanggal_mulai) = '$bulan'AND YEAR(tanggal_mulai) = '$tahun'";
$resultTotalTransaksi = mysqli_query($conn, $queryTotalTransaksi);
$rowTotalTransaksi = mysqli_fetch_assoc($resultTotalTransaksi);
$totalTransaksi = $rowTotalTransaksi['total'];

$queryTotalPendapatan = "SELECT SUM(total_harga) AS total FROM transaksi WHERE MONTH(tanggal_mulai) = '$bulan'AND YEAR(tanggal_mulai) = '$tahun'";
$resultTotalPendapatan = mysqli_query($conn, $queryTotalPendapatan);
$rowTotalPendapatan = mysqli_fetch_assoc($resultTotalPendapatan);
$totalPendapatan = $rowTotalPendapatan['total'] ?? 0;

$queryTotalMobil = "SELECT COUNT(*) AS total FROM mobil WHERE status = 'Aktif'"; // Ganti 'Aktif' jika status berbeda (misalnya 'tersedia')
$resultTotalMobil = mysqli_query($conn, $queryTotalMobil);
$rowTotalMobil = mysqli_fetch_assoc($resultTotalMobil);
$totalMobil = $rowTotalMobil['total'];

$queryTotalSopir = "SELECT COUNT(*) AS total FROM sopir WHERE status = 'Aktif'"; // Ganti 'Aktif' jika status berbeda (misalnya 'tersedia')
$resultTotalSopir = mysqli_query($conn, $queryTotalSopir);
$rowTotalSopir = mysqli_fetch_assoc($resultTotalSopir);
$totalSopir = $rowTotalSopir['total'];

// Query mobil sering dipesan hari ini
$queryMobil = "
    SELECT m.nama_mobil, COUNT(t.id) AS jumlah_pemesanan
    FROM transaksi t
    JOIN mobil m ON t.mobil_id = m.id
    WHERE MONTH(t.tanggal_mulai) = '$bulan'
    AND YEAR(t.tanggal_mulai) = '$tahun'
    GROUP BY t.mobil_id
    ORDER BY jumlah_pemesanan DESC
";
$resultMobil = mysqli_query($conn, $queryMobil);

// Query sopir sering dipesan hari ini - DIPERBAIKI
$querySopir = "
    SELECT s.nama AS nama, COUNT(t.id) AS total
    FROM transaksi t
    JOIN sopir s ON t.sopir_id = s.id
    WHERE MONTH(t.tanggal_mulai) = '$bulan'
    AND YEAR(t.tanggal_mulai) = '$tahun'
    GROUP BY t.sopir_id
    ORDER BY total DESC
";
$resultSopir = mysqli_query($conn, $querySopir);

// Query laporan pendapatan hari ini
$queryLaporan = "
    SELECT 
        t.tanggal_mulai,
        m.nama_mobil,
        p.nama AS nama_pelanggan,
        t.total_harga,
        t.status
    FROM transaksi t
    JOIN mobil m ON t.mobil_id = m.id
    JOIN pelanggan p ON t.pelanggan_id = p.id
    WHERE MONTH(t.tanggal_mulai) = '$bulan'
    AND YEAR(t.tanggal_mulai) = '$tahun'
    ORDER BY t.tanggal_mulai DESC
";
$resultLaporan = mysqli_query($conn, $queryLaporan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian | Admin</title>
    <link rel="stylesheet" href="css/laporan.css"> <!-- Sesuaikan path CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
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
            <li class="active"><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li  class="active"><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
              <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            <?php endif; ?>

          </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="header">
            <h1>Laporan Bulanan - <?= date('F Y'); ?></h1>
        </div>

         <div class="options">
            <a href="laporan.php" class="option-btn active">Laporan</a>
            <a href="grafik_pendapatan.php" class="option-btn">Grafik</a>
        </div>

        <!-- Ringkasan -->
        <div class="summary-grid">
            <div class="summary-card">
                <i class="fa-solid fa-file-invoice"></i>
                <div>
                    <p>Total Transaksi Hari Ini</p>
                    <h3><?= htmlspecialchars($totalTransaksi); ?></h3>
                </div>
            </div>
            <div class="summary-card income">
                <i class="fa-solid fa-money-bill-wave"></i>
                <div>
                    <p>Pendapatan Hari Ini</p>
                    <h3>Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></h3>
                </div>
            </div>
            <div class="summary-card">
                <i class="fa-solid fa-car"></i>
                <div>
                    <p>Mobil Aktif</p>
                    <h3><?= htmlspecialchars($totalMobil); ?></h3>
                </div>
            </div>
            <div class="summary-card">
                <i class="fa-solid fa-user-tie"></i>
                <div>
                    <p>Sopir Aktif</p>
                    <h3><?= htmlspecialchars($totalSopir); ?></h3>
                </div>
            </div>
        </div>

        <!-- Mobil Sering Dipesan -->
        <section class="report-section">
            <h2><i class="fa-solid fa-car"></i> Mobil yang Sering Dipesan Hari Ini</h2>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Nama Mobil</th>
                        <th>Jumlah Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultMobil) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($resultMobil)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_mobil']); ?></td>
                                <td><?= htmlspecialchars($row['jumlah_pemesanan']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">Tidak ada pemesanan mobil hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Sopir Sering Dipesan -->
        <section class="report-section">
            <h2><i class="fa-solid fa-star"></i> Sopir yang Sering Dipesan Hari Ini</h2>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Nama Sopir</th>
                        <th>Total Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultSopir) > 0): ?>
                        <?php
                        $rank = 1;
                        while ($s = mysqli_fetch_assoc($resultSopir)): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['nama']); ?></td>
                                <td><?= htmlspecialchars($s['total']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">Tidak ada pemesanan sopir hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Laporan Pendapatan -->
        <section class="report-section">
            <h2><i class="fa-solid fa-coins"></i> Laporan Pendapatan Hari Ini</h2>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Mobil</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultLaporan) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($resultLaporan)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['tanggal_mulai']); ?></td>
                                <td><?= htmlspecialchars($row['nama_mobil']); ?></td>
                                <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge <?= htmlspecialchars($row['status']); ?>">
                                        <?= htmlspecialchars(ucfirst($row['status'])); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Tidak ada transaksi hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

</body>
</html>