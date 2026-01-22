<?php
session_start(); // Pastikan session dimulai
include('../app/config/database.php');

/* ================= STATISTIK ================= */

// Unit Mobil
$qMobil = $conn->query("SELECT COUNT(*) AS total FROM mobil");
$totalMobil = $qMobil->fetch_assoc()['total'] ?? 0;

// Pelanggan
$qPelanggan = $conn->query("SELECT COUNT(*) AS total FROM pelanggan");
$totalPelanggan = $qPelanggan->fetch_assoc()['total'] ?? 0;

// Transaksi
$qTransaksi = $conn->query("SELECT COUNT(*) AS total FROM transaksi");
$totalTransaksi = $qTransaksi->fetch_assoc()['total'] ?? 0;

// Pendapatan (hanya selesai)
$qPendapatan = $conn->query("
    SELECT SUM(total_harga) AS total 
    FROM transaksi 
    WHERE status = 'Selesai'
");
$totalPendapatan = $qPendapatan->fetch_assoc()['total'] ?? 0;

/* ================= TRANSAKSI TERBARU ================= */
$qData = $conn->query("
    SELECT 
        t.id,
        p.nama AS pelanggan,
        m.nama_mobil,
        t.tanggal_mulai,
        t.status
    FROM transaksi t
    JOIN pelanggan p ON t.pelanggan_id = p.id
    JOIN mobil m ON t.mobil_id = m.id
    ORDER BY t.tanggal_mulai DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpati Trans Makassar</title>
  <link rel="stylesheet" href="../admin/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a2e0a2c6f1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
          <div class="sidebar-header">
          <div class="logo">
              <img src="../img/logo2.png" alt="Logo Simpati Trans">
          </div>
    </div>
          <ul class="menu">
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li class="active"><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="riwayat_transaksi.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li class="active"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
            <?php endif; ?>

          
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          </ul>
        </aside>

        <!-- Konten Utama -->
        <main class="main-content">
        <header class="header">
            <h1>Dashboard Admin</h1>
            <div class="admin-info">
            <?php if (isset($_SESSION['username'])): ?>
              <i class="fa-solid fa-user-circle"></i>
              <span><?= htmlspecialchars($_SESSION['username']); ?></span>
            <?php else: ?>
              <a href="login.php" class="btn-login">
                <i class="fa-solid fa-sign-in-alt"></i> Login
              </a>
            <?php endif; ?>
            </div>
        </header>
    
          <!-- Statistik -->
          <section class="stats">
            <div class="card">
              <i class="fa-solid fa-car"></i>
              <div>
                <h3><?= $totalMobil ?></h3>
                <p>Unit Mobil</p>
              </div>
            </div>

            <div class="card">
              <i class="fa-solid fa-users"></i>
              <div>
                <h3><?= $totalPelanggan ?></h3>
                <p>Pelanggan</p>
              </div>
            </div>

            <div class="card">
              <i class="fa-solid fa-handshake"></i>
              <div>
                <h3><?= $totalTransaksi ?></h3>
                <p>Transaksi</p>
              </div>
            </div>

            <div class="card">
              <i class="fa-solid fa-chart-line"></i>
              <div>
                <h3>Rp <?= number_format($totalPendapatan,0,',','.') ?></h3>
                <p>Pendapatan</p>
              </div>
            </div>
          </section>
    
          <!-- Data Tabel -->
          <section class="data-section">
            <h2>Data Transaksi Terbaru</h2>
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Pelanggan</th>
                  <th>Mobil</th>
                  <th>Tanggal Sewa</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>

              <?php if ($qData->num_rows > 0): ?>
                <?php while($row = $qData->fetch_assoc()): ?>
                <tr>
                  <td>#<?= str_pad($row['id'],3,'0',STR_PAD_LEFT) ?></td>
                  <td><?= htmlspecialchars($row['pelanggan']) ?></td>
                  <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
                  <td><?= date('Y-m-d', strtotime($row['tanggal_mulai'])) ?></td>
                  <td>
                    <?php
                      if ($row['status'] == 'Selesai') {
                        echo '<span class="status success">Selesai</span>';
                      } elseif ($row['status'] == 'Proses') {
                        echo '<span class="status pending">Proses</span>';
                      } else {
                        echo '<span class="status cancel">Batal</span>';
                      }
                    ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" style="text-align:center;">Belum ada transaksi</td>
                </tr>
              <?php endif; ?>

              </tbody>
            </table>
          </section>

        </main>
      </div>
</body>
</html>