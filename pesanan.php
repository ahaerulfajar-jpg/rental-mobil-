<?php
session_start();
include('app/config/database.php');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// User harus login
if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php");
    exit;
}

$pelanggan_id = $_SESSION['user_id'];

$query = "
    SELECT t.*, m.nama_mobil, m.gambar_mobil, m.harga_sewa_per_hari
    FROM transaksi t
    JOIN mobil m ON t.mobil_id = m.id
    WHERE t.pelanggan_id = '$pelanggan_id'
    ORDER BY t.id DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Simpati Trans Makassar</title>
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1a73e8">
    <meta name="description" content="Lihat pesanan mobil Anda di Simpati Trans Makassar">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Simpati Trans">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" href="img/logo1.png">
    <link rel="apple-touch-icon" href="img/logo1.png">
    
    <link rel="stylesheet" href="css/pesanan.css?v=2.1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Header -->
  <header id="header2" class="header2">
    <div id="top-bar2" class="top-bar2">
      <div class="container">
        <span>Email: info@simpatitrans.com</span>
        <span>Telepon: +62 812 3456 7890</span>
      </div>
    </div>

    <!-- Navbar Versi 2 -->
    <nav id="navbar2" class="navbar2">
    <div class="container">
      <div class="logo">
        <img src="img/logo1.png" alt="Logo Simpati Trans">
      </div>
      <button class="hamburger-menu" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <ul class="nav-links2">
        <li><a href="index.php">Beranda</a></li>
        <li><a href="tentangkami.php">Tentang Kami</a></li>
        <li><a href="daftarmobil.php">Daftar Mobil</a></li>
        <li><a href="tour.php">Tour</a></li>
        <li><a href="pesanan.php">Pesanan Saya</a></li>
        <li><a href="#">FAQ</a></li>
        <!-- =========== LOGIN / USERNAME =========== -->
        <?php if (!isset($_SESSION['user_id'])) : ?>
              <li><a href="login1.php" class="btn-login">Login</a></li>
              <?php else: ?>
              <li class="user-menu">
                <a href="#" class="user-btn">
                  <i class="fa-solid fa-user"></i> 
                  <?= $_SESSION['nama']; ?>
                </a>

                <ul class="dropdown-menu profile-dropdown">
                <div class="profile-info">
                    <img src="img/person.jpg" class="profile-avatar">
                    <div class="profile-text">
                        <span class="profile-name"><?= $_SESSION['nama']; ?></span>
                        <span class="profile-email"><?= $_SESSION['email']; ?></span>
                    </div>
                </div>
                  <li><a href="logout1.php">Logout</a></li>
                </ul>
              </li>
          <?php endif; ?>
      </ul>
    </div>
  </nav>
  </header>

  <!-- Mobile Menu Overlay -->
  <div class="mobile-menu-overlay"></div>

  <!-- Mobile Menu -->
  <div class="mobile-menu">
    <ul class="mobile-nav-links">
      <li><a href="index.php">Beranda</a></li>
      <li><a href="tentangkami.php">Tentang Kami</a></li>
      <li><a href="daftarmobil.php">Daftar Mobil</a></li>
      <li><a href="tour.php">Tour</a></li>
      <li><a href="pesanan.php">Pesanan Saya</a></li>
      <li><a href="#">FAQ</a></li>
      <?php if (!isset($_SESSION['user_id'])) : ?>
        <li><a href="login1.php" class="btn-login">Login</a></li>
      <?php else: ?>
        <li class="mobile-user-menu">
          <a href="#" class="mobile-user-btn">
            <i class="fa-solid fa-user"></i> <?= $_SESSION['nama']; ?>
          </a>
        </li>
        <li><a href="logout1.php">Logout</a></li>
      <?php endif; ?>
    </ul>
  </div>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">
    <div class="container">
      <h1>Pesanan Saya</h1>
      <p class="subtitle">Daftar pesanan mobil yang telah Anda lakukan</p>

      <div class="order-list">
    <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>

        <div class="order-card">
            <img src="img/<?= $row['gambar_mobil']; ?>" alt="Gambar Mobil">

            <div class="order-info">
                <h3><?= $row['nama_mobil']; ?></h3>

                <p><i class="fa-solid fa-calendar"></i>
                    <?= date('d M Y', strtotime($row['tanggal_mulai'])) ?> -
                    <?= date('d M Y', strtotime($row['tanggal_selesai'])) ?>
                </p>

                <p><i class="fa-solid fa-clock"></i>
                    Durasi:
                    <?php
                    $days = (strtotime($row['tanggal_selesai']) - strtotime($row['tanggal_mulai'])) / (60*60*24);
                    echo $days . " Hari";
                    ?>
                </p>

                <p><i class="fa-solid fa-money-bill"></i>
                    Total: <b>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></b>
                </p>

                <span class="status <?= strtolower($row['status']); ?>">
                    <?= ucfirst($row['status']); ?>
                </span>
            </div>

            <div class="order-actions">
                <a href="app/pelanggan/detail.pesanan.php?id=<?= $row['id']; ?>" class="btn-detail">Lihat Detail</a>

                <?php if ($row['status'] == 'Menunggu'): ?>
                    <a href="batal_pesanan.php?id=<?= $row['id']; ?>" class="btn-cancel"
                       onclick="return confirm('Batalkan pesanan ini?')">Batalkan</a>
                <?php elseif ($row['status'] == 'Selesai'): ?>
                    <a href="hapus_pesanan.php?id=<?= $row['id']; ?>" class="btn-delete"
                       onclick="return confirm('Hapus pesanan ini?')">Hapus</a>
                <?php endif; ?>
            </div>
        </div>

        <?php endwhile; ?>
          <?php else: ?>
              <p class="no-data">Belum ada pesanan.</p>
          <?php endif; ?>
          </div>
  </main>

  <!-- ===== FOOTER ===== -->
  <footer class="footer">
    <p>© 2025 CV. Simpati Trans Makassar | Rental Mobil Murah di Makassar</p>
  </footer>


  <script src="js/dashboard.js"></script>
  <script src="js/pwa.js"></script>

</body>
</html>