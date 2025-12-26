<?php
include('app/config/database.php');
$result = $conn->query("SELECT * FROM mobil ORDER BY id DESC LIMIT 8");

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
  
  <!-- PWA Meta Tags -->
  <meta name="theme-color" content="#1a73e8">
  <meta name="description" content="Rental Mobil Makassar - Sewa mobil dengan mudah dan nyaman. Melayani perjalanan wisata, dinas pemerintahan, dan lainnya.">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Simpati Trans">
  
  <!-- PWA Manifest -->
  <link rel="manifest" href="/manifest.json">
  
  <!-- Favicon and Icons -->
  <link rel="icon" type="image/png" href="img/logo1.png">
  <link rel="apple-touch-icon" href="img/logo1.png">
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/style.css">
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

  <header id="header" class="header">
  <!-- Top Bar -->
  <div id="top-bar" class="top-bar">
      <div class="container">
          <span>Email: info@simpatitrans.com</span>
          <span>Telepon: +62 812 3456 7890</span>
      </div>
  </div>

  <!-- Navbar -->
  <nav id="navbar" class="navbar">
      <div class="container">
        <div class="logo">
          <img src="img/logo1.png" alt="Logo Simpati Trans">
        </div>
          <ul class="nav-links">
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
          </div>
  </nav>
</header>

  <!-- Banner -->
  <section class="banner">
    <div class="slide-banner">
      <div class="slide active" style="background-image: url('img/banner.jpg');"></div>
      <div class="slide" style="background-image: url('img/banner 3.jpg');"></div>
      <div class="slide" style="background-image: url('img/banner 4.jpg');"></div>
      <div class="slide" style="background-image: url('img/banner 2.jpg');"></div>
    </div>
  
    <div class="banner-text">
      <h2>Sewa Mobil Makassar</h2>
      <p>Melayani Perjalanan Wisata, Dinas Pemerintahan, dan Lainnya</p>
      <a href="#layanan" class="btn">Lihat Mobil</a>
    </div>
  </section>

  <!--Iklan-->
  <section class="iklan">
    <!-- Baris Iklan Kecil -->
    <div class="iklan-row">
      <div class="iklan-card">
        <img src="img/promo.png" alt="Iklan 1">
      </div>
      <div class="iklan-card">
        <img src="img/promo 5.jpg" alt="Iklan 2">
      </div>
    </div>
  </section>

  <!-- Iklan Besar -->
  <section class="iklan-besar">
    <div class="slide-container">
      <div class="slide-iklan">
        <img src="img/promo 2.jpg" alt="Iklan Besar 1">
      </div>
      <div class="slide-iklan">
        <img src="img/promo 3.jpg" alt="Iklan Besar 2">
      </div>
      <div class="slide-iklan">
        <img src="img/promo 6.jpg" alt="Iklan Besar 3">
      </div>
      <div class="slide-iklan">
        <img src="img/promo 7.jpg" alt="Iklan Besar 4">
      </div>
    </div>
  
    <div class="dots-container">
      <span class="dot-iklan active" data-slide="0"></span>
      <span class="dot-iklan" data-slide="1"></span>
      <span class="dot-iklan" data-slide="2"></span>
      <span class="dot-iklan" data-slide="3"></span>
    </div>
  </section>
  
  <!-- Tentang Kami -->
  <section id="tentang" class="tentang">
    <h2> <span class="highlight">Tentang</span> Kami</h2>
    <p class="subtitle">About us Simpati Trans Makassar</p>
    <p class="intro">
      <strong>CV. Simpati Trans</strong>: Kami hadir untuk melayani Anda di bidang jasa transportasi. Dengan demikian, kami siap 
      memberikan pelayanan yang berkualitas untuk setiap pelanggan kami. Simpati Trans menyediakan kendaraan mobil 
      dan bus yang terawat, bersih, aman, serta nyaman dengan harga yang sangat terjangkau. Lebih jauh lagi, kami 
      juga mempunyai driver yang berpengalaman dan ramah.
    </p>

    <p class="intro">
      Komitmen kami yakni memberikan pelayanan terbaik serta senantiasa menjaga loyalitas kepada pelanggan. Oleh karena itu, 
      <strong>CV. Simpati Trans</strong> – Layanan Penyedia Rental Mobil Makassar siap memenuhi kebutuhan Anda sebagai pelanggan. Tidak hanya itu, 
      <strong>CV. Simpati Trans</strong> – Sewa Mobil Makassar juga menjamin setiap unit mobil yang akan Anda rental karena kami rutin melakukan servis 
      secara berkala di setiap unit mobil yang kami miliki. Dengan demikian, kami memastikan kepuasan dan kenyamanan perjalanan Anda 
      saat merental/sewa mobil kami, khususnya Anda yang berada di Makassar dan sekitarnya.
    </p>

    <div class="wrapper-tentang">
      <div class="card-tentang">
        <h3>Rental Mobil Makassar: Solusi Praktis untuk Perjalanan Anda</h3>
        <p>Jika Anda merencanakan perjalanan ke Makassar, Simpati Trans – Rental Mobil Makassar 
           bisa menjadi pilihan ideal untuk memastikan perjalanan Anda berjalan lancar dan nyaman.
           Dalam hal ini, dengan berbagai opsi kendaraan yang tersedia, Anda dapat menyesuaikan pilihan mobil sesuai 
           dengan kebutuhan dan anggaran Anda.
        </p>
      </div>

      <div class="card-tentang">
        <h3 class="warna">Fleksibilitas dan Kenyamanan dalam Perjalanan</h3>
        <p>Pertama-tama, menyewa mobil di Simpati Trans Makassar memberikan fleksibilitas yang sangat berharga. 
          Sebagai contoh, jika Anda ingin menjelajahi berbagai destinasi wisata seperti Pantai Losari atau 
          Taman Nasional Bantimurung, memiliki mobil sendiri memungkinkan Anda pergi kapan saja tanpa bergantung 
          pada jadwal transportasi umum. Di samping itu, Anda dapat memilih kendaraan yang sesuai dengan ukuran 
          kelompok Anda, mulai dari sedan kompak hingga SUV yang lebih luas.
        </p>
      </div>

      <div class="card-tentang">
        <h3>Perbandingan Harga dan Penawaran</h3>
        <p>Selanjutnya, harga rental mobil Makassar sangat bervariasi, sehingga penting untuk membandingkan penawaran 
          dari beberapa penyedia layanan. Oleh karena itu, pastikan untuk memeriksa tarif sewa serta syarat dan ketentuan 
          yang berlaku. Beberapa penyedia mungkin menawarkan paket khusus atau diskon untuk periode tertentu. Dengan demikian, 
          Anda dapat memperoleh penawaran terbaik yang sesuai dengan anggaran Anda.
        </p>
      </div>

      <div class="card-tentang">
        <h3 class="warna">Kualitas Layanan dan Ulasan Pelanggan</h3>
        <p>Di samping itu, kualitas layanan pelanggan juga perlu diperhatikan. Misalnya, beberapa perusahaan rental mobil 
          menawarkan layanan antar-jemput dari dan ke bandara atau hotel, yang tentunya meningkatkan kenyamanan perjalanan Anda.
           Namun, penting untuk membaca ulasan dari pelanggan sebelumnya agar Anda dapat memastikan bahwa Anda mendapatkan layanan 
           yang memuaskan.
        </p>
      </div>

      <div class="card-tentang">
        <h3>Persiapan dan Pemilihan Penyedian</h3>
        <p>Terakhir, setelah memilih perusahaan rental mobil yang tepat, pastikan untuk memahami semua syarat dan ketentuan sewa sebelum 
          menandatangani kontrak. Dengan cara ini, Anda bisa menghindari biaya tambahan yang tidak terduga dan menikmati perjalanan 
          Anda di Makassar dengan tenang.
        </p>
      </div>

      <div class="card-tentang">
        <h3 class="warna">Kesimpulan</h3>
        <p>Dengan demikian, rental mobil di Makassar menawarkan kemudahan dan kenyamanan yang dibutuhkan untuk menjelajahi kota dan sekitarnya 
          dengan bebas. Jadi, jangan ragu untuk menyewa mobil dan manfaatkan kebebasan penuh dalam perjalanan Anda. Untuk informasi lebih lanjut 
          dan penawaran terbaik, hubungi Simpati Trans penyedia rental mobil terpercaya di Makassar hari ini!
        </p>
      </div>

    </div>
  </section>

  <section class="kenapa-kami">
    <div class="container">
      <h2><span class="highlight">Kenapa</span> Memilih <span class="highlight">Kami</span> ?</h2>
      <p class="subtitle">Why Choose US Simpati Trans</p>
  
      <p class="intro">
        Simpati Trans Makassar – <strong>Sewa Mobil Makassar</strong> menawarkan layanan sewa kendaraan jangka pendek 
        dengan durasi harian atau mingguan. Dengan layanan ini, Anda dapat menikmati perjalanan yang nyaman dan fleksibel 
        sesuai kebutuhan Anda.
      </p>
  
      <p class="intro">
        Kami memiliki sopir yang tidak hanya ramah tetapi juga sangat terampil di bidangnya. Oleh karena itu, Anda tidak 
        perlu khawatir tentang rute atau jalan yang akan dilalui; sopir kami akan memastikan perjalanan Anda berjalan lancar. 
        Selain itu, Simpati Trans juga menetapkan harga yang sangat bersahabat dengan pelayanan terbaik.
      </p>
  
      <div class="fitur-wrapper">
        <div class="fitur-card">
          <h3>Biaya Sewa Terjangkau</h3>
          <p><strong>Rental Mobil Makassar – Sewa Mobil Makassar</strong></p>
          <p>
            Harga sewa yang murah dengan pelayanan maksimal meningkatkan nilai ekonomis perjalanan Anda. 
            <strong>Layanan Penyewaan Mobil Termurah di Makassar</strong>.
          </p>
        </div>
  
        <div class="fitur-card">
          <h3>Merek/Tipe Mobil Beragam</h3>
          <p><strong>Menyediakan Mobil & Bus</strong></p>
          <p>
            Menyediakan berbagai pilihan jenis mobil untuk memenuhi kebutuhan berkendara Anda. 
            <strong>Rental Mobil Gowa / Sewa Mobil & Bus</strong>.
          </p>
        </div>
  
        <div class="fitur-card">
          <h3>Proses Penyewaan Mudah</h3>
          <p><strong>Kemanapun Tujuan Anda</strong></p>
            <p>Dengan hanya membawa Fotocopy KTP & Kartu Keluarga, proses penyewaan Anda akan kami setujui. 
            <strong>Rent Car Makassar – Penyedia Transportasi</strong>.
          </p>
        </div>
      </div>
    </div>
  </section>
  

<section id="layanan" class="layanan">
    <h2> <span class="highlight">Layanan</span> Kami</h2>
    <p class="subtitle">Daftar Mobil & Bus</p>
    <div class="grid">
        <div class="grid-mobil">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="card-mobil">
                    <div class="img-wrapper">
                        <img src="img/<?= htmlspecialchars($row['gambar_mobil']); ?>" alt="<?= htmlspecialchars($row['nama_mobil']); ?>">
                        <span class="badge"><?= htmlspecialchars($row['status']); ?></span>
                    </div>
                    <div class="info">
                        <h3 class="nama-mobil"><?= htmlspecialchars($row['nama_mobil']); ?></h3>
                        <?php if ($row['status'] === 'Tersedia') { ?>
                            <a href="detailmobil.php?id=<?= $row['id']; ?>" class="btn-order">Pesan Sekarang</a>
                        <?php } else { ?>
                            <a href="#" class="btn-order disabled" onclick="return false;">Tidak Tersedia</a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>


  <!--About-->
  <section class="about">
    <div class="about-left">
      <h2>Kami Sangat Senang Melayani Anda</h2>
      <div class="profil">
        <img src="img/profil.jpeg" alt="CEO Simpati Trans Makassar">
      </div>
      <h3>Bapak Syam</h3>
      <p>CEO - Simpati Trans (CV. Simpati Trans Makassar)</p>
      <p class="contact">Kontak : Telepon & WA +62 812 4226 5207 </p>
      <div class="social-media">
        <i class="fa-brands fa-facebook"></i>
        <i class="fa-brands fa-instagram"></i>
        <i class="fa-brands fa-whatsapp"></i>
      </div>
      <a href="#" class="map-link">Lihat Map</a>
    </div>
  
    <div class="about-right">
      <div class="hero-content">
        <div class="hero-text">
          <h1>Solusi Transportasi dan Wisata Terbaik Anda di Makassar</h1>
          <p>
            Percayakan perjalanan Anda kepada Simpati Trans. Kami siap menjadi mitra terbaik
            untuk kebutuhan transportasi dan wisata Anda di Sulawesi Selatan. Dengan layanan kami,
            perjalanan Anda akan menjadi lebih nyaman, aman, dan berkesan!
          </p>
          <a href="daftarmobil.php" class="btn-rent">
            <i class="fa-solid fa-car-side"></i> Rent Now
          </a>
        </div>
    
        <div class="hero-image">
          <img src="img/alphard2025.png" alt="Mobil Simpati Trans">
        </div>
      </div>
  </section>>

<!-- Testimoni + Galeri -->
<section class="testimoni-galeri">
  <div class="testimoni">
    <div class="container">
      <h3 class="judul-testimoni">Galeri <span class="merah">Testimoni</span></h3>
      <p class="subjudul">Rental mobil rekomended di Makassar.</p>

      <!-- Apa Kata Mereka -->
      <h3 class="kata-mereka">
        Apa Kata <span class="orange">Mereka ?</span>
      </h3>
      <p class="desc">Mengenai Simpati Trans Makassar</p>

      <!-- Isi Testimoni -->
      <div class="isi-testimoni activate">
        <p class="quote">
          Salam Slankers (Peace). <br>
          Terimakasih kepada crew <strong>CV. SIMPATI TRANS</strong> yang telah mengantar kami bersama 
          Rombongan ke Toraja untuk membuat video klip Terbaru Kami.
        </p>

        <div class="foto-testimoni">
          <img src="img/testimoni1.png" alt="Kaka Slank">
        </div>

        <h4 class="nama">Kaka Slank</h4>
        <p class="profesi">Vocalis Slank</p>
      </div>

      <div class="isi-testimoni">
        <p class="quote">
          Setia memakai jasa rental mobil simpati trans, belum pernah merasa kecewa karena simpati trans
          selalu memberikan pelayanan yang terbaik dan cepat, dan untuk semua drivernya sangat ramah.untuk 
          perjalanan bisnis dan liburan saya selalu memakai jasa SIMPATI TRANS sampai sekarang.sukses terus 
          untuk CV.SIMPATI TRANS
        </p>

        <div class="foto-testimoni">
          <img src="img/testimoni2.jpg" alt="armand maulana">
        </div>

        <h4 class="nama">Armand Maualana</h4>
        <o class="profesi">Artis ibu kota</o>
      </div>

      <div class="isi-testimoni">
        <p class="quote">
          Simpati trans paling recommed soal sewa mobil Tidak ada masalah sama sekali selama pemakaian. 
          Terima kasih untuk pelayanannya yang memuaskan dan reliable.
        </p>

        <div class="foto-testimoni">
          <img src="img/testimoni3.jpg" alt="haerul fajar">
        </div>

        <h4 class="nama">A.Haerul Fajar.S</h4>
        <o class="profesi">Mahasiswa</o>
      </div>

      <div class="isi-testimoni">
        <p class="quote">
          SukseS selalu CV.SIMPATI TRANS MAKASSAR
        </p>

        <div class="foto-testimoni">
          <img src="img/testimoni4.jpg" alt="haerul fajar">
        </div>

        <h4 class="nama">A.Nurjihan</h4>
        <o class="profesi">Mahasiswi</o>
      </div>

      <!-- Navigasi panah -->
      <div class="navigasi">
        <button class="btn-nav">&#9664;</button>
        <button class="btn-nav">&#9654;</button>
      </div>
    </div>
  </div>

  <!-- Kolom Galeri -->
  <div class="galeri">
    <h2><span class="judul-dokumentasi">Galeri</span> Dokumentasi</h2>
    <div class="galeri-grid">
      <img src="img/galeri/galeri1.jpg" alt="galeri 1">
      <img src="img/galeri/galeri2.jpg" alt="galeri 2">
      <img src="img/galeri/galeri3.jpg" alt="galeri 3">
      <img src="img/galeri/galeri4.jpg" alt="galeri 4">
      <img src="img/galeri/galeri5.jpg" alt="galeri 5">
      <img src="img/galeri/galeri6.jpg" alt="galeri 6">
      <img src="img/galeri/galeri7.jpg" alt="galeri 7">
      <img src="img/galeri/galeri8.jpg" alt="galeri 8">
      <img src="img/galeri/galeri9.jpg" alt="galeri 9">
      <img src="img/galeri/galeri10.jpg" alt="galeri 10">
      <img src="img/galeri/galeri11.jpg" alt="galeri 11">
      <img src="img/galeri/galeri12.jpg" alt="galeri 12">
    </div>
  </div>
</section>

  

  <!-- Kontak -->
  <section id="kontak" class="kontak">
    <h2>Temui Kami - Rental Mobil Makassar</h2>
    <div class="kontak-container">
      <!-- Map -->
      <div class="map-container">
        <iframe src="https://www.google.com/maps/d/embed?mid=1C1uTz3ZkPtnmEtZ_u0ziUaJnxx1RUejg&ehbc=2E312F"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
      </div>
  
      <!-- Instagram Card -->
      <div class="social-card">
        <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/rentalsimpatitrans/" 
        data-instgrm-version="12" 
        style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:undefinedpx;height:undefinedpx;max-height:100%; width:undefinedpx;">
        <div style="padding:16px;"> 
          <a id="main_link" href="https://www.instagram.com/officialdenpommakassar/" 
          style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; 
          text-decoration:none; width:100%;" target="_blank"> 
          <div style=" display: flex; flex-direction: row; align-items: center;"> 
            <div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; 
            margin-right: 14px; width: 40px;"></div> 
            <div style="display: flex; flex-direction: column; 
            flex-grow: 1; justify-content: center;"> 
            <div style=" background-color: #F4F4F4;
             border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div> 
             <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div></div></div><div style="padding: 19% 0;"></div> <div style="display:block; height:50px; margin:0 auto 12px; width:50px;"><svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-511.000000, -20.000000)" fill="#000000"><g><path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631"></path></g></g></g></svg></div><div style="padding-top: 8px;"> <div style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;"> View this post on Instagram</div></div><div style="padding: 12.5% 0;"></div> <div style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;"><div> <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);"></div> <div style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;"></div> <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);"></div></div><div style="margin-left: 8px;"> <div style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;"></div> <div style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)"></div></div><div style="margin-left: auto;"> <div style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);"></div> <div style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);"></div> <div style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);"></div></div></div> <div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;"> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;"></div> <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;"></div></div></a><p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;"><a href="https://www.instagram.com/officialdenpommakassar/" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;" target="_blank">Shared post</a> on <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;">Time</time></p></div></blockquote><script async src="https://www.instagram.com/embed.js"></script><div style="overflow: auto; position: absolute; height: 0pt; width: 0pt;"></div></div><style>.boxes3{height:175px;width:153px;} #n img{max-height:none!important;max-width:none!important;background:none!important} #inst i{max-height:none!important;max-width:none!important;background:none!important}</style></div>
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
