<?php
session_start();
include('../app/config/database.php');

// Proteksi role pemilik
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pemilik') {
    header("Location: index.php");
    exit;
}

// Ambil bulan & tahun (default bulan ini)
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

// Query grafik pendapatan bulanan
$query = "
    SELECT 
        DATE(tanggal_mulai) AS tanggal,
        SUM(total_harga) AS pendapatan,
        COUNT(id) AS total_transaksi
    FROM transaksi
    WHERE 
        MONTH(tanggal_mulai) = '$bulan'
        AND YEAR(tanggal_mulai) = '$tahun'
        AND status = 'Selesai'
    GROUP BY DATE(tanggal_mulai)
    ORDER BY tanggal ASC
";

$result = mysqli_query($conn, $query);

$labels = [];
$pendapatan = [];
$transaksi = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = date('d', strtotime($row['tanggal']));
    $pendapatan[] = (int)$row['pendapatan'];
    $transaksi[] = (int)$row['total_transaksi'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpati Trans Makassar</title>
  <link rel="stylesheet" href="css/grafik.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a2e0a2c6f1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li class="active"><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
            <?php endif; ?>

            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          </ul>
    </aside>

    <main class="main-content">
        <div class="grafik-container">
        <h1><i class="fa-solid fa-chart-line"></i> Grafik Pendapatan</h1>

    <div class="options">
        <a href="laporan.php" class="option-btn">Laporan</a>
        <a href="grafik_pendapatan.php" class="option-btn active">Grafik</a>
     </div>

    <!-- Filter -->
    <form method="GET" class="filter-form">
        <select name="bulan">
            <?php for ($i = 1; $i <= 12; $i++): ?>
                <option value="<?= sprintf('%02d', $i); ?>" <?= $bulan == sprintf('%02d', $i) ? 'selected' : ''; ?>>
                    <?= date('F', mktime(0, 0, 0, $i, 10)); ?>
                </option>
            <?php endfor; ?>
        </select>

        <select name="tahun">
            <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                <option value="<?= $y; ?>" <?= $tahun == $y ? 'selected' : ''; ?>>
                    <?= $y; ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit">Tampilkan</button>
    </form>

    <!-- Grafik -->
    <div class="chart-box">
        <canvas id="grafikPendapatan"></canvas>
    </div>
</div>

<script>
    const LABELS = <?= json_encode($labels); ?>;
    const DATA_PENDAPATAN = <?= json_encode($pendapatan); ?>;
    const DATA_TRANSAKSI = <?= json_encode($transaksi); ?>;
</script>
</main>

<script src="js/grafik.js"></script>
</body>
</html>