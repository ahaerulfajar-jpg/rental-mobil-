<?php
include('../project/app/config/database.php');
$id = $_GET['id'];

$data = $conn->query("SELECT * FROM mobil WHERE id=$id")->fetch_assoc();

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
  <link rel="stylesheet" href="css/detailmobil.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
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
    </div>
  </nav>
  </header>

  <div class="container-detail">

    <!-- ==== LEFT IMAGE ==== -->
    <div class="image-box">
        <img src="img/<?= $data['gambar_mobil']; ?>" class="mobil-img">
    </div>

    <!-- ==== RIGHT CONTENT ==== -->
    <div class="detail-right">
        <h1><?= $data['nama_mobil']; ?></h1>

        <h3 class="sub-title">Spesifikasi :</h3>

        <div class="spec-grid">

        <div class="spec-item">
        <i class="fa-solid fa-wheelchair"></i>
        <p class="spec-title">Kapasitas</p>
        <span><?= $data['kapasitas']; ?> Orang </span>
    </div>

    <div class="spec-item">
        <i class="fa-solid fa-gear"></i>
        <p class="spec-title">Transmisi</p>
        <span><?= $data['transmisi']; ?></span>
    </div>

    <div class="spec-item">
        <i class="fa-solid fa-gas-pump"></i>
        <p class="spec-title">Bahan Bakar</p>
        <span><?= $data['bahan_bakar']; ?></span>
    </div>

    <div class="spec-item">
        <i class="fa-solid fa-tags"></i>
        <p class="spec-title">Tipe Mobil</p>
        <span><?= $data['tipe_mobil']; ?></span>
    </div>

    <div class="spec-item">
        <i class="fa-solid fa-shield-alt"></i>
           <div>
          <p>Asuransi</p>
          <span><?= $data['asuransi'] ?? 'YES'; ?></span>
        </div>
    </div>

    <div class="spec-item">
        <i class="fa-solid fa-user-tie"></i>
        <div>
          <p>Pengemudi</p>
              <span><?= $data['pengemudi'] ?? 'YES'; ?></span>
        </div>
    </div>


        </div>

        <!-- STATUS MOBIL -->
        <div class="status">
            Status :
            <?php if ($data['status'] == "Tersedia") { ?>
                <span class="status-ready">Tersedia</span>
            <?php } else { ?>
                <span class="status-rent">Disewa</span>
            <?php } ?>
        </div>

        <p class="deskripsi">
            Kendaraan dan pengemudi sudah diverifikasi serta mengikuti protokol kebersihan untuk menjaga kenyamanan.
        </p>

        <!-- TOMBOL PESAN -->
        <a 
            href="<?= $data['status'] == 'Tersedia' ? 'pesan.php?id='.$data['id'] : '#'; ?>" 
            class="btn-pesan <?= $data['status'] != 'Tersedia' ? 'btn-disabled' : ''; ?>"
        >
            <?= $data['status'] == 'Tersedia' ? 'Pesan Sekarang' : 'Tidak Tersedia'; ?>
        </a>
    </div>

</div>

<!-- SECTION TAB MENU -->
<div class="detail-tabs">
    <button class="tab-btn active" data-target="overview">Overview</button>
    <button class="tab-btn" data-target="asuransi">Informasi Asuransi</button>
</div>

<!-- CONTENT OVERVIEW -->
<div id="overview" class="tab-content active">
    <p>
        Kendaraan pilihan kami siap diandalkan untuk kebutuhan perjalanan Anda. 
        Kami memastikan setiap unit selalu dalam kondisi terbaik, bersih, dan
        menjalani pengecekan rutin untuk menjaga kenyamanan dan keamanan Anda.
        Untuk setiap perjalanan, Anda dapat memilih layanan tambahan seperti pengemudi profesional,
        perjalanan luar kota, serta berbagai opsi fleksibel untuk memenuhi kebutuhan Anda.
    </p>
</div>

<!-- CONTENT INFORMASI ASURANSI -->
<div id="asuransi" class="tab-content">
    <p>
        Demi keamanan dan keselamatan Anda selama menggunakan layanan kami, 
        kami melindungi Anda dengan proteksi asuransi. Ketentuan asuransi ini 
        bersifat mengikat dan mengatur perihal layanan sewa yang diberikan.
        Seluruh kendaraan telah diasuransikan oleh perusahaan asuransi 
        <strong>Comprehensive</strong> sesuai ketentuan polis yang berlaku.
        Asuransi ini berlaku untuk layanan sewa tanpa pengemudi maupun dengan pengemudi,
        termasuk cakupan kerusakan, kehilangan, dan ketentuan lain yang telah diatur.
        Perlindungan asuransi dimulai ketika Anda mulai menggunakan kendaraan dan
        berakhir saat masa sewa selesai.
    </p>
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

  <script src="js/dashboard.js"></script>
</body>
</html>