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
    <link rel="stylesheet" href="../../css/detailpesanan.css">
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
        <img src="../../img/logo1.png" alt="Logo Simpati Trans">
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
              <li><a href="../../login1.php" class="btn-login">Login</a></li>
              <?php else: ?>
              <li class="user-menu">
                <a href="#" class="user-btn">
                  <i class="fa-solid fa-user"></i> 
                  <?= $_SESSION['nama']; ?>
                </a>

                <ul class="dropdown-menu">
                  <li><a href="profil.php">Profil</a></li>
                  <li><a href="../../logout1.php">Logout</a></li>
                </ul>
              </li>
          <?php endif; ?>
        </div>
    </div>
  </nav>
  </header>

  <script src="../../js/dashboard.js"></script>
</body>
</html>