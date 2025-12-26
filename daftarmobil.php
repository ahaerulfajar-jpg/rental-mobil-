<?php
include('app/config/database.php');
$result = $conn->query("SELECT * FROM mobil ORDER BY id DESC");

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mobil - Simpati Trans Makassar</title>
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1a73e8">
    <meta name="description" content="Daftar mobil tersedia untuk disewa di Simpati Trans Makassar">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Simpati Trans">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" href="img/logo1.png">
    <link rel="apple-touch-icon" href="img/logo1.png">
    
    <link rel="stylesheet" href="css/daftarmobil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php
if (isset($_SESSION['pelanggan'])) {
    echo "<!-- Debug: Session pelanggan ada: " . print_r($_SESSION['pelanggan'], true) . " -->";
} else {
    echo "<!-- Debug: Session pelanggan tidak ada -->";
}
?>

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
          <ul class="nav-links2">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="tentangkami.php">Tentang Kami</a></li>
            <li><a href="daftarmobil.php">Daftar Mobil</a></li> <!-- Diperbaiki: ekstensi .php -->
            <li><a href="tour.html">Tour</a></li>
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
          </div>
      </ul>
    </div>
  </nav>
  </header>

   <!-- Daftar Mobil -->
<section class="section-mobil">
    <div class="container-mobil">
        <h2 class="judul-mobil">Daftar Mobil</h2>

        <div class="mobil-grid">

            <?php while ($m = $result->fetch_assoc()) { ?>
            
            <div class="mobil-card">

                 <!-- Status Badge -->
                 <div class="status-badge <?= strtolower($m['status']); ?>">
                    <?= $m['status'] == 'Tersedia' ? 'Tersedia' : 'Tidak Tersedia'; ?>
                </div>

                <!-- Gambar Mobil -->
                <img src="img/<?= $m['gambar_mobil']; ?>" class="mobil-img">

                <!-- Nama Mobil -->
                <h3 class="mobil-nama"><?= $m['nama_mobil']; ?></h3>

                <!-- Harga Mobil -->
                <p class="mobil-harga">
                    Rp <?= number_format($m['harga_sewa_per_hari'], 0, ',', '.'); ?> / Hari
                </p>

                <!-- Spesifikasi Mobil -->
                <div class="mobil-spec">
                    <p><i class="fa-solid fa-wheelchair"></i></i> Kapasitas: 
                        <span><?= $m['kapasitas']; ?> Orang </span>
                    </p>

                    <p><i class="fa-solid fa-calendar"></i> Tahun: 
                        <span><?= $m['tahun'] ?? 'Tidak ada data'; ?></span>
                    </p>

                    <p><i class="fa-solid fa-gear"></i> Transmisi:
                        <span><?= $m['transmisi']; ?></span>
                    </p>

                    <p><i class="fa-solid fa-gas-pump"></i> Bahan Bakar:
                        <span><?= $m['bahan_bakar'] ?? 'Bensin'; ?></span>
                    </p>
                </div>

                <!-- Button Pesan -->
                <a 
                    href="<?= $m['status'] == 'Tersedia' ? 'detailmobil.php?id='.$m['id'] : '#'; ?>" 
                    class="mobil-btn <?= $m['status'] != 'Tersedia' ? 'btn-disabled' : ''; ?>"
                >
                    <?= $m['status'] == 'Tersedia' ? 'Pesan Sekarang' : 'Disewa'; ?>
                </a>

            </div>

            <?php } ?>

        </div>
    </div>
</section>

    <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <!-- Kolom 1: Info Perusahaan -->
      <div class="footer-col">
        <img src="img/logo2.png" alt="Logo Simpati Trans" class="footer-logo">
        <p>Rental Mobil Murah di Makassar</p>
        <a href="#" class="profile-link">
          <i class="fa-solid fa-arrow-right-to-bracket"></i> Lihat Profil
        </a>
      </div>
  
      <!-- Kolom 2: Alamat -->
      <div class="footer-col">
        <h3>Alamat</h3>
        <div class="line"></div>
        <p><i class="fa-solid fa-location-dot"></i> Jl. Nikel Raya A9 Makassar</p>
        <img src="img/bri.png" alt="BRI" class="bank-logo">
        <p>No. Rek: <b>221901000558561</b><br>An. CV. SIMPATI TRANS</p>
      </div>
  
      <!-- Kolom 3: Kontak -->
      <div class="footer-col">
        <h3>Kontak</h3>
        <div class="line"></div>
        <p><i class="fa-solid fa-phone"></i> Telephone : 0812 4226 5207</p>
        <p><i class="fa-brands fa-whatsapp"></i> Whatsapp : 0812 4226 5207</p>
        <p><i class="fa-solid fa-envelope"></i> Email : simpatitransmks03@gmail.com</p>
      </div>
  
      <!-- Kolom 4: Pos Terbaru -->
      <div class="footer-col">
        <h3>Pos Terbaru</h3>
        <div class="line"></div>
        <div class="post-item">
          <img src="img/mobil1.jpg" alt="Post 1">
          <a href="#">Rental Mobil Makassar Armada Terlengkap | Harga Murah 2025</a>
        </div>
        <div class="post-item">
          <img src="img/mobil2.jpg" alt="Post 2">
          <a href="#">Sewa Mobil Lepas Kunci Makassar | Harga Murah 2025</a>
        </div>
      </div>
    </div>
  
    <div class="footer-bottom">
      <p>©2024. CV Simpati Trans. All Rights Reserved.</p>
    </div>
  </footer>

    <script src="js/dashboard.js"></script>
    <script src="js/pwa.js"></script>
    
    <?php include 'includes/chat_button.php'; ?>
</body>
</html>
