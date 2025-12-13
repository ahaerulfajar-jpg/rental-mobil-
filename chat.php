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
  <title>Chat - Simpati Trans Makassar</title>
  <!-- CSS untuk Header dan Navbar -->
  <link rel="stylesheet" href="css/daftarmobil.css">
  <!-- CSS untuk Footer -->
  <link rel="stylesheet" href="css/style.css">
  <!-- CSS untuk Chat -->
  <link rel="stylesheet" href="css/chat.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

    <!-- Navbar -->
    <nav id="navbar2" class="navbar2">
      <div class="container">
        <div class="logo">
          <a href="index.php">
            <img src="img/logo1.png" alt="Logo Simpati Trans">
          </a>
        </div>
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

  <!-- Chat Page -->
  <div class="chat-page">
    <div class="chat-page-header">
      <h1>
        <span class="chat-status"></span>
        <i class="fa-solid fa-comments"></i>
        Chat dengan Simpati Trans
      </h1>
      <p>
        Tanyakan apapun tentang layanan rental mobil kami. Kami siap membantu Anda!
      </p>
    </div>

    <div class="chat-page-body">
      <!-- Messages will be inserted here by JavaScript -->
    </div>

    <div class="chat-page-footer">
      <div class="container">
        <div class="chat-page-input-wrapper">
          <input 
            type="text" 
            class="chat-page-input" 
            placeholder="Ketik pesan Anda di sini..." 
            autocomplete="off"
          >
        </div>
        <button class="chat-page-send-button" aria-label="Kirim Pesan">
          <i class="fa-solid fa-paper-plane"></i>
        </button>
      </div>
    </div>
  </div>

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

  <!-- Dashboard JavaScript (untuk header, dropdown, dll) -->
  <script src="js/dashboard.js"></script>
  
  <!-- Chat JavaScript -->
  <script src="js/chat.js"></script>

</body>
</html>

