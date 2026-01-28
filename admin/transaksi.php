<?php 
session_start();
include('../app/config/database.php'); 
$dbPath = __DIR__ . '/../app/config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php"); 
    exit;
}

if (!file_exists($dbPath)) {
    die("File database.php tidak ditemukan di: $dbPath. Periksa struktur folder.");
}
include_once $dbPath;

if (!isset($conn) || !$conn) {
    die("Koneksi database gagal. Pastikan database.php membuat \$conn (mysqli) dan kredensial benar.");
}

$sql = "SELECT t.*, m.nama_mobil, m.gambar_mobil 
        FROM transaksi t
        LEFT JOIN mobil m ON t.mobil_id = m.id
        ORDER BY t.id DESC";

$result = $conn->query($sql);
if ($result === false) {
    die("Query gagal: " . $conn->error);
}
if ($result === false) {
    // query gagal: tampilkan pesan (atau log), tapi hentikan output tabel agar tidak menghasilkan warning
    $errorMsg = $conn->error;
    die("Query gagal: $errorMsg");
}

?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpati Trans Makassar</title>
  <link rel="stylesheet" href="../admin/css/transaksi.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a2e0a2c6f1.js" crossorigin="anonymous"></script>
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
            <li class="active"><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
            <li><a href="profil.php"><i class="fa-solid fa-person"></i> Profil</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li class="active"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
              <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            <?php endif; ?>

          </ul>
        </aside>

  <!-- Main Content -->
  <main class="main-content">
    <h1>Daftar Transaksi</h1>
    <div class="transaksi-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>

            <div class="transaksi-card">

                <img src="../img/<?= $row['gambar_mobil']; ?>" class="mobil-img">

                <div class="transaksi-info">
                    <h3><?= $row['nama_mobil']; ?></h3>

                    <p><i class="fa-solid fa-user"></i> <b><?= $row['nama_pelanggan']; ?></b></p>

                    <p><i class="fa-solid fa-calendar"></i> 
                        <?= date('d M Y', strtotime($row['tanggal_mulai'])) ?> -
                        <?= date('d M Y', strtotime($row['tanggal_selesai'])) ?>
                    </p>

                    <p><i class="fa-solid fa-clock"></i>
                        <?php
                            $days = (strtotime($row['tanggal_selesai']) - strtotime($row['tanggal_mulai'])) / (60*60*24);
                            echo $days . " Hari";
                        ?>
                    </p>

                    <p><i class="fa-solid fa-money-bill"></i> 
                         <b>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></b>
                    </p>

                    <span class="status <?= strtolower($row['status']); ?>">
                        <?= $row['status']; ?>
                    </span>
                </div>

                <div class="transaksi-actions">
                    <a href="../app/admin/detail_transaksi.php?id=<?= $row['id']; ?>" class="btn-detail">Lihat Detail</a>
                </div>

            </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-data">Belum ada transaksi.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>