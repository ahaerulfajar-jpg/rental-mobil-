<?php
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
    <title>Simpati Trans Makassar</title>
    <link rel="stylesheet" href="css/tentangkami.css?v=2.1">
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
      <button class="hamburger-menu" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <ul class="nav-links2">
        <li><a href="index.php">Beranda</a></li>
        <li><a href="tentangkami.php">Tentang Kami</a></li>
        <li><a href="daftarmobil.php">Daftar Mobil</a></li> <!-- Diperbaiki: ekstensi .php -->
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
            <i class="fa-solid fa-user"></i>
            <?= $_SESSION['nama']; ?>
          </a>
        </li>
        <li><a href="logout1.php">Logout</a></li>
      <?php endif; ?>
    </ul>
  </div>

      <section class="about-section">
        <div class="about-container">
          <div class="about-text">
            <h2> <span class="judul">CV. Simpati</span> Trans</h2>
            <p>
              CV. Simpati Trans adalah penyedia transportasi di Makassar yang menawarkan layanan rental mobil tanpa sopir, dengan sopir, serta paket wisata ke Makassar dan Toraja. Dengan unit kendaraan lengkap dan layanan profesional, kami siap memenuhi kebutuhan transportasi Anda.
            </p>
            <p>
              Kami menyediakan berbagai jenis kendaraan, seperti Toyota Avanza, Mitsubishi Xpander, Honda Brio, Kijang Innova, Innova Zenix, Toyota Alphard, Fortuner, Pajero, Bus Hiace, dan Bus Medium Pariwisata. Layanan kami meliputi rental mobil fleksibel dan paket wisata menarik.
            </p>
            <p>
              Kami menawarkan unit kendaraan lengkap, sopir berpengalaman, harga kompetitif, dan fleksibilitas layanan untuk memastikan kenyamanan perjalanan Anda.
            </p>
            <p>
              Berbasis di Makassar, kami melayani pelanggan di seluruh Indonesia untuk kebutuhan transportasi lokal dan wisata.
            </p>
            <p><strong><span class="paragraph">Nikmati perjalanan nyaman</span> bersama CV. Simpati Trans.</strong></p>
          </div>
      
          <div class="about-image">
            <img src="img/rent.jpg" alt="Mobil CV Simpati Trans" />
          </div>
        </div>
      </section>

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
      
      <?php include 'includes/chat_button.php'; ?>
</body>
</html>