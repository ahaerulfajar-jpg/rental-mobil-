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
    <link rel="stylesheet" href="css/tour.css">
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
        </div>
    </div>
  </nav>
  </header>

  <!-- Paket Tour -->
  <section class="paket-tour">
    <div class="container">
      <div class="tour-content">
        <div class="tour-text">
          <h2>Paket Tour Makassar 4 hari 3 Malam (4D3N)</h2>
          <p>
            Paket Tour Makassar 4 hari 3 Malam (4D3N) adalah program 
            wisata yang telah disiapkan untuk Anda yang ingin menikmati
            wisata terbaik dengan harga terjangkau. Dalam paket Wisata di 
            kota Makassar dan sekitarnya, Anda akan diajak berpetualang menelusuri
            icon – icon dan keindahan kota Makassar selain itu kami akan mengajak
            berpetualang di PEGUNUNGAN KARST terbesar ketiga di dunia dan menikmati 
            keindahan AIR TERJUN BANTIMURUNG yang tersohor. Setelah itu, Anda akan 
            diajak merasakan sejuknya udara pegunungan di MALINO dan perkampungan ala 
            BENUA EROPA disini kita akan melintasi hamparan kebun teh yang luas dan bersantai 
            ditengah hutan pinus yang indah. Setelah puas menikmati wisata alam, Anda akan diajak 
            menjelajahi tempat sejarah dan menikmati kuliner khas Makassar dan suasana pantai di Makassar. 
            Semua keseruan itu kami sajikan di dalam paket ini.
          </p>
          <button class="book-btn">Book Now</button>
        </div>

        <div class="tour-gallery">
          <img src="img/wisata/BANTIMURUNG.jpg" alt="Bantimurung">
          <img src="img/wisata/KEBUN-TEH.jpg" alt="Kebun Teh">
          <img src="img/wisata/KONRO.jpg" alt="Kuliner">
          <img src="img/wisata/LOSARI.jpg" alt="Pantai Losari">
          <img src="img/wisata/ROTERDAM.jpg" alt="Fort Rotterdam">
          <img src="img/wisata/RAMMANG.jpg" alt="Rammang-Rammang">
        </div>
      </div>
    </div>
  </section>

  <section class="carousel">
    <div class="carousel-track">
      <div class="card"><img src="img/wisata/HOTTEL.jpg" alt=""></div>
      <div class="card"><img src="img/wisata/COTO-MAKASSAR.jpg" alt=""></div>
      <div class="card"><img src="img/wisata/TATOR.jpg" alt=""></div>
      <div class="card"><img src="img/wisata/SOP-SAUDARA.jpg" alt=""></div>
      <div class="card"><img src="img/wisata/RELIGI.jpg" alt=""></div>
      <div class="card"><img src="img/wisata/KAMPUNG-EROPA.jpg" alt=""></div>
      <div class="card"><img src="img/wisata/COTO-MAKASSAR.jpg" alt=""></div>

      <!-- Duplikasi untuk efek loop -->
    <div class="carousel-item"><img src="img/wisata/HOTTEL.jpg" alt=""></div>
    <div class="carousel-item"><img src="img/wisata/COTO-MAKASSAR.jpg"></div>
    <div class="carousel-item"><img src="img/wisata/TATOR.jpg" alt=""></div>
    </div>
    <div class="carousel-dots"></div>
  </section>

  <!--informasi-->
  <section class="tour-info">
    <div class="container">
      <div class="info-grid">
        <div class="info-left">
          <p>
            Kami memahami pentingnya kenyamanan selama perjalanan Anda. Oleh karena itu,
            paket tour Simpati Trans dilengkapi dengan:
          </p>
          <ul>
            <li>Kendaraan yang nyaman dan ber-AC.</li>
            <li>Sopir profesional yang berpengalaman.</li>
            <li>Itinerary yang fleksibel sesuai kebutuhan Anda.</li>
          </ul>
  
          <h3>Mengapa Memilih Simpati Trans?</h3>
          <ol>
            <li><strong>Pengalaman Lokal:</strong> Tim kami memiliki pengetahuan mendalam tentang destinasi di Makassar dan Toraja.</li>
            <li><strong>Harga Terjangkau:</strong> Nikmati perjalanan seru tanpa menguras kantong.</li>
            <li><strong>Pelayanan Ramah:</strong> Fokus pada kepuasan pelanggan di setiap perjalanan.</li>
          </ol>
        </div>
  
        <div class="info-right">
          <h3>Hubungi Kami untuk Reservasi Tour</h3>
          <p>
            Siap menjelajahi keindahan Makassar dan Toraja? Hubungi Simpati Trans untuk paket tour yang sesuai dengan kebutuhan Anda:
          </p>
  
          <ul>
            <li><strong>Alamat:</strong> Jl. Nikel Raya A9, Makassar</li>
            <li><strong>Telepon/WhatsApp:</strong> 081242265207</li>
            <li><strong>Email:</strong> simpatitransmks03@gmail.com</li>
            <li><strong>Website:</strong> www.simpatitransrent.com</li>
          </ul>
  
          <p>
            Nikmati perjalanan wisata yang nyaman dan penuh kesan bersama Simpati Trans.
            Pilihan terbaik untuk petualangan Anda di Sulawesi Selatan!
          </p>
        </div>
      </div>
    </div>
  </section>

  <hr class="section-divider">
  
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

</body>
</html>