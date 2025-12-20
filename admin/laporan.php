<?php
session_start();
include '../app/config/database.php';

/* =========================
   CEK LOGIN ADMIN
========================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

// Total transaksi
$qTransaksi = mysqli_query($conn, "SELECT COUNT(*) total FROM transaksi");
$totalTransaksi = mysqli_fetch_assoc($qTransaksi)['total'] ?? 0;

$qPendapatan = mysqli_query($conn, "
    SELECT SUM(total_harga) total 
    FROM transaksi 
    WHERE status = 'Selesai'
");
$totalPendapatan = mysqli_fetch_assoc($qPendapatan)['total'] ?? 0;

// Mobil aktif (Tersedia)
$qMobil = mysqli_query($conn, "
    SELECT COUNT(*) total 
    FROM mobil 
    WHERE status = 'Tersedia'
");
$totalMobil = mysqli_fetch_assoc($qMobil)['total'] ?? 0;

// Sopir aktif (tersedia)
$qSopir = mysqli_query($conn, "
    SELECT COUNT(*) total 
    FROM sopir 
    WHERE status = 'tersedia'
");
$totalSopir = mysqli_fetch_assoc($qSopir)['total'] ?? 0;

/* =========================
   LAPORAN TRANSAKSI
========================= */
$qLaporan = mysqli_query($conn, "
    SELECT 
        t.tanggal_mulai,
        t.total_harga,
        t.status,
        m.nama_mobil,
        p.nama AS nama_pelanggan
    FROM transaksi t
    JOIN mobil m ON t.mobil_id = m.id
    JOIN pelanggan p ON t.pelanggan_id = p.id
    ORDER BY t.tanggal_mulai DESC
");

/* =========================
   SOPIR TERFAVORIT
========================= */
$qSopirFavorit = mysqli_query($conn, "
    SELECT s.nama, COUNT(*) total
    FROM transaksi t
    JOIN sopir s ON t.sopir_id = s.id
    WHERE t.sopir_id IS NOT NULL
    GROUP BY s.id
    ORDER BY total DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpati Trans Makassar</title>
  <link rel="stylesheet" href="../admin/css/laporan.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a2e0a2c6f1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
          <div class="sidebar-header">
          <div class="logo">
              <img src="../img/logo2.png" alt="Logo Simpati Trans">
          </div>
    </div>

          <ul class="menu">
            <li><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li class="active"><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
            <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          </ul>
        </aside>

<div class="report-container">

<h1 class="report-title">
  <i class="fa-solid fa-chart-line"></i> Laporan Admin
</h1>

<!-- ================= RINGKASAN ================= -->
<div class="summary-grid">
  <div class="summary-card">
    <i class="fa-solid fa-file-invoice"></i>
    <div>
      <p>Total Transaksi</p>
      <h3><?= htmlspecialchars($totalTransaksi) ?></h3>
    </div>
  </div>

  <div class="summary-card income">
    <i class="fa-solid fa-money-bill-wave"></i>
    <div>
      <p>Total Pendapatan</p>
      <h3>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></h3>
    </div>
  </div>

  <div class="summary-card">
    <i class="fa-solid fa-car"></i>
    <div>
      <p>Mobil Aktif</p>
      <h3><?= htmlspecialchars($totalMobil) ?></h3>
    </div>
  </div>

  <div class="summary-card">
    <i class="fa-solid fa-user-tie"></i>
    <div>
      <p>Sopir Aktif</p>
      <h3><?= htmlspecialchars($totalSopir) ?></h3>
    </div>
  </div>
</div>

<!-- ================= LAPORAN TRANSAKSI ================= -->
<section class="report-section">
<h2><i class="fa-solid fa-coins"></i> Laporan Pendapatan</h2>

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
<?php while ($row = mysqli_fetch_assoc($qLaporan)) : ?>
<tr>
  <td><?= htmlspecialchars($row['tanggal_mulai']) ?></td>
  <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
  <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
  <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
  <td>
    <span class="badge <?= htmlspecialchars($row['status']) ?>">
      <?= htmlspecialchars(ucfirst($row['status'])) ?>
    </span>
  </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</section>

<!-- ================= SOPIR FAVORIT ================= -->
<section class="report-section">
<h2><i class="fa-solid fa-star"></i> Sopir Paling Sering Dipesan</h2>

<table class="report-table">
<thead>
<tr>
  <th>Peringkat</th>
  <th>Nama Sopir</th>
  <th>Total Pesanan</th>
</tr>
</thead>
<tbody>
<?php 
$rank = 1;
while ($s = mysqli_fetch_assoc($qSopirFavorit)) : ?>
<tr>
  <td><?= htmlspecialchars($rank++) ?></td>
  <td><?= htmlspecialchars($s['nama']) ?></td>
  <td><?= htmlspecialchars($s['total']) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</section>

</div>
</body>
</html>