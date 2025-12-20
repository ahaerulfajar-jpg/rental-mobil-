<?php
include '../config/database.php'; 
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

$id_pesanan = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_pesanan <= 0) {
    die("ID pesanan tidak valid.");
}

$query = "
    SELECT 
        t.*, 
        p.nama AS nama_pelanggan, p.email AS email_pelanggan, 
        m.nama_mobil, m.gambar_mobil, 
        s.nama AS nama_sopir, s.telepon AS telepon_sopir  -- Tambahkan telepon sopir
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id  -- Ganti jika kolom berbeda
    LEFT JOIN mobil m ON t.mobil_id = m.id          -- Ganti jika kolom berbeda
    LEFT JOIN sopir s ON t.sopir_id = s.id          -- Ganti jika kolom berbeda
    WHERE t.id = $id_pesanan
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error query: " . mysqli_error($conn)); 
}

if (mysqli_num_rows($result) == 0) {
    die("Data pesanan tidak ditemukan.");
}

$data = mysqli_fetch_assoc($result);

$mobil = [
    'nama_mobil' => $data['nama_mobil'] ?? 'Tidak diketahui',
    'gambar_mobil' => $data['gambar_mobil'] ?? 'default.jpg'
];

$pelanggan = [
    'nama' => $data['nama_pelanggan'] ?? 'Tidak diketahui',
    'email' => $data['email_pelanggan'] ?? 'Tidak diketahui'
];

$transaksi = [
    'tanggal_mulai' => $data['tanggal_mulai'] ?? '-',
    'tanggal_selesai' => $data['tanggal_selesai'] ?? '-',
    'telepon' => $data['telepon'] ?? '-',
    'pakai_sopir' => $data['pakai_sopir'] ?? 'tidak',
    'jam_mulai' => $data['jam_mulai'] ?? '-',
    'alamat_jemput' => $data['alamat_jemput'] ?? '-',
    'catatan' => $data['catatan'] ?? '-',
    'total_harga' => $data['total_harga'] ?? 0,
    'status' => $data['status'] ?? 'pending'
];

// Perbaiki array sopir: Sertakan nama dan telepon
$sopir = null;
if ($data['nama_sopir'] && $data['telepon_sopir']) {
    $sopir = [
        'nama' => $data['nama_sopir'],
        'telepon' => $data['telepon_sopir']
    ];
}

mysqli_close($conn);
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
        <li><a href="../../index.php">Beranda</a></li>
        <li><a href="../../tentangkami.php">Tentang Kami</a></li>
        <li><a href="../../daftarmobil.php">Daftar Mobil</a></li>
        <li><a href="../../tour.php">Tour</a></li>
        <li><a href="../../pesanan.php">Pesanan Saya</a></li>
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

  <div class="detail-container">
    <div class="card">
        <!-- Bagian kiri (gambar mobil) -->
        <div class="left">
            <img src="../../img/<?= htmlspecialchars($mobil['gambar_mobil']); ?>" class="car-image" alt="Gambar Mobil">
        </div>

        <!-- Bagian kanan (detail pesanan) -->
        <div class="right">
            <h1><?= htmlspecialchars($mobil['nama_mobil']); ?></h1>
            <h3>Detail Transaksi</h3>

            <div class="spec-grid">
                <!-- Nama Pelanggan -->
                <div class="spec-box">
                    <i class="fa-solid fa-user"></i>
                    <p class="label">Nama Pelanggan</p>
                    <p class="value"><?= htmlspecialchars($pelanggan['nama']); ?></p>
                </div>

                <!-- Email -->
                <div class="spec-box">
                    <i class="fa-solid fa-envelope"></i>
                    <p class="label">Email</p>
                    <p class="value"><?= htmlspecialchars($pelanggan['email']); ?></p>
                </div>

                <!-- Tanggal Mulai -->
                <div class="spec-box">
                    <i class="fa-solid fa-calendar"></i>
                    <p class="label">Tanggal Mulai</p>
                    <p class="value"><?= htmlspecialchars($transaksi['tanggal_mulai']); ?></p>
                </div>

                <!-- Tanggal Selesai -->
                <div class="spec-box">
                    <i class="fa-solid fa-calendar-check"></i>
                    <p class="label">Tanggal Selesai</p>
                    <p class="value"><?= htmlspecialchars($transaksi['tanggal_selesai']); ?></p>
                </div>

                <!-- Telepon -->
                <div class="spec-box">
                    <i class="fa-solid fa-phone"></i>
                    <p class="label">Telepon</p>
                    <p class="value"><?= htmlspecialchars($transaksi['telepon']); ?></p>
                </div>

                <!-- Pakai Sopir -->
                <div class="spec-box">
                    <i class="fa-solid fa-id-card"></i>
                    <p class="label">Pakai Sopir?</p>
                    <p class="value"><?= htmlspecialchars(ucfirst($transaksi['pakai_sopir'])); ?></p>
                </div>

                <!-- Detail Sopir -->
                <div class="spec-box full">
                    <i class="fa-solid fa-user-tie"></i>
                    <p class="label">Detail Sopir</p>
                    <p class="value">
                        <?php if ($transaksi['pakai_sopir'] === 'ya' && $sopir): ?>
                            Nama: <?= htmlspecialchars($sopir['nama']); ?><br>
                            Telepon: <?= htmlspecialchars($sopir['telepon']); ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Jam Mulai -->
                <div class="spec-box">
                    <i class="fa-solid fa-clock"></i>
                    <p class="label">Jam Mulai</p>
                    <p class="value"><?= htmlspecialchars($transaksi['jam_mulai']); ?></p>
                </div>

                <!-- Alamat Jemput -->
                <div class="spec-box full">
                    <i class="fa-solid fa-location-dot"></i>
                    <p class="label">Alamat Jemput</p>
                    <p class="value"><?= htmlspecialchars($transaksi['alamat_jemput']); ?></p>
                </div>

                <!-- Catatan -->
                <div class="spec-box full">
                    <i class="fa-solid fa-file"></i>
                    <p class="label">Catatan</p>
                    <p class="value"><?= htmlspecialchars($transaksi['catatan'] ?: '-'); ?></p>
                </div>

                <!-- Total Harga -->
                <div class="spec-box">
                    <i class="fa-solid fa-receipt"></i>
                    <p class="label">Total Harga</p>
                    <p class="value price">
                        Rp <?= number_format((float)$transaksi['total_harga'], 0, ',', '.'); ?>
                    </p>
                </div>

                <!-- Status -->
                <div class="spec-box">
                    <i class="fa-solid fa-circle-info"></i>
                    <p class="label">Status</p>
                    <p class="value status <?= htmlspecialchars($transaksi['status']); ?>">
                        <?= htmlspecialchars(ucfirst($transaksi['status'])); ?>
                    </p>
                </div>
            </div>

            <!-- Tombol untuk admin -->
            <div class="button-row">
                <?php if ($transaksi['status'] === 'pending'): ?>
                    <a href="approve.php?id=<?= $id_pesanan; ?>" class="btn approve">
                        <i class="fa-solid fa-check"></i> Approve
                    </a>
                    <a href="reject.php?id=<?= $id_pesanan; ?>" class="btn delete">
                        <i class="fa-solid fa-times"></i> Reject
                    </a>
                <?php elseif ($transaksi['status'] === 'disetujui'): ?>
                    <a href="complete.php?id=<?= $id_pesanan; ?>" class="btn complete">
                        <i class="fa-solid fa-check-circle"></i> Mark as Complete
                    </a>
                <?php endif; ?>
                <a href="../../pesanan.php" class="btn back">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

  <script src="../../js/dashboard.js"></script>
</body>
</html>