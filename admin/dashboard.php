<?php
session_start();
include('../app/config/database.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pemilik') {
    header("Location: login.php");
    exit;
}

/* TOTAL MOBIL */
$qMobil = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mobil");
$totalMobil = mysqli_fetch_assoc($qMobil)['total'];

/* TOTAL TRANSAKSI */
$qTransaksi = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi");
$totalTransaksi = mysqli_fetch_assoc($qTransaksi)['total'];

/* TOTAL PENDAPATAN (SELESAI SAJA) */
$qPendapatan = mysqli_query($conn, "
    SELECT SUM(total_harga) AS total 
    FROM transaksi 
    WHERE status = 'selesai'
");
$totalPendapatan = mysqli_fetch_assoc($qPendapatan)['total'] ?? 0;

/* TOTAL ADMIN */
$qAdmin = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM users 
    WHERE role = 'admin'
");
$totalAdmin = mysqli_fetch_assoc($qAdmin)['total'];

/* MOBIL PALING LAKU */
$qMobilLaku = mysqli_query($conn, "
    SELECT m.nama_mobil, COUNT(t.id) AS jumlah
    FROM transaksi t
    JOIN mobil m ON t.mobil_id = m.id
    GROUP BY m.id
    ORDER BY jumlah DESC
    LIMIT 1
");
$mobilLaku = mysqli_fetch_assoc($qMobilLaku);

/* TRANSAKSI TERBESAR */
$qTertinggi = mysqli_query($conn, "
    SELECT nama_pelanggan, total_harga, tanggal_mulai
    FROM transaksi
    WHERE status = 'Selesai'
    ORDER BY total_harga DESC
    LIMIT 1
");
$tertinggi = mysqli_fetch_assoc($qTertinggi);

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpati Trans Makassar</title>
  <link rel="stylesheet" href="../admin/css/dashboard.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a2e0a2c6f1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">

    <!-- SIDEBAR PEMILIK -->
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
            <li><a href="laporan_harian.php"><i class="fa-solid fa-file-lines"></i> Laporan </a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li  class="active"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
              <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            <?php endif; ?>

          </ul>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

    <div class="header">
      <h1>Dashboard</h1>
      <div class="admin-info">
        <i class="fa-solid fa-user"></i>
        Pemilik Simpati Trans
      </div>
    </div>

    <!-- STATISTIK -->
    <div class="stats">
      <div class="card">
        <i class="fa-solid fa-car"></i>
        <div>
          <p>Total Mobil</p>
          <h3><?= $totalMobil ?></h3>
        </div>
      </div>

      <div class="card">
        <i class="fa-solid fa-handshake"></i>
        <div>
          <p>Total Transaksi</p>
          <h3><?= $totalTransaksi ?></h3>
        </div>
      </div>

      <div class="card">
        <i class="fa-solid fa-money-bill-wave"></i>
        <div>
          <p>Total Pendapatan</p>
          <h3>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h3>
        </div>
      </div>

      <div class="card">
        <i class="fa-solid fa-user-gear"></i>
        <div>
          <p>Total Admin</p>
          <h3><?= $totalAdmin ?></h3>
        </div>
      </div>
    </div>

  <!-- RINGKASAN PENTING -->
<div class="summary-card">

    <div class="summary-header">
        <h2>Ringkasan Penting</h2>
    </div>

    <div class="summary-content">

        <!-- Mobil Paling Laku -->
        <div class="summary-item">
            <div class="summary-image">
                <?php if (!empty($mobilLaku['gambar_mobil'])): ?>
                    <img src="../img/<?= htmlspecialchars($mobilLaku['gambar_mobil']); ?>" alt="<?= htmlspecialchars($mobilLaku['nama_mobil']); ?>">
                <?php else: ?>
                    <img src="../img/nothing.jpg" alt="Tidak ada gambar">
                <?php endif; ?>
            </div>

            <div class="summary-info">
                <span class="label">Mobil Paling Laku</span>
                <h3><?= $mobilLaku['nama_mobil'] ?? '-' ?></h3>
                <p><?= $mobilLaku['jumlah'] ?? 0 ?> transaksi</p>
            </div>
        </div>

        <!-- Transaksi Tertinggi -->
        <?php if ($tertinggi): ?>
        <div class="summary-transaction">
            <span class="label">Transaksi Tertinggi</span>

            <ul>
                <li><strong>Pelanggan:</strong> <?= htmlspecialchars($tertinggi['nama_pelanggan']); ?></li>
                <li><strong>Total:</strong> Rp <?= number_format($tertinggi['total_harga'], 0, ',', '.'); ?></li>
                <li><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($tertinggi['tanggal_mulai'])); ?></li>
            </ul>
        </div>
        <?php else: ?>
        <div class="summary-empty">
            <p>Belum ada transaksi selesai</p>
        </div>
        <?php endif; ?>
    </div>
</div>

  </main>
</div>

</body>
</html>