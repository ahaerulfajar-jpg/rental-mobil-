<?php
session_start(); // Pastikan session dimulai
include('../app/config/database.php'); // Sertakan koneksi database jika diperlukan

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpati Trans Makassar</title>
  <link rel="stylesheet" href="../admin/css/dashboard.css">
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
            <li class="active"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
            <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          </ul>
        </aside>

        <!-- Konten Utama -->
        <main class="main-content">
          <header class="header">
            <h1>Dashboard Admin</h1>
            <div class="admin-info">
              <?php if (isset($_SESSION['admin_username'])): ?>
                <!-- Jika sudah login, tampilkan username -->
                <i class="fa-solid fa-user-circle"></i>
                <span><?= htmlspecialchars($_SESSION['admin_username']); ?></span>
              <?php else: ?>
                <!-- Jika belum login, tampilkan tombol login -->
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
                <h3>15</h3>
                <p>Unit Mobil</p>
              </div>
            </div>
            <div class="card">
              <i class="fa-solid fa-users"></i>
              <div>
                <h3>120</h3>
                <p>Pelanggan</p>
              </div>
            </div>
            <div class="card">
              <i class="fa-solid fa-handshake"></i>
              <div>
                <h3>230</h3>
                <p>Transaksi</p>
              </div>
            </div>
            <div class="card">
              <i class="fa-solid fa-chart-line"></i>
              <div>
                <h3>Rp 25 Jt</h3>
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
                <tr>
                  <td>#001</td>
                  <td>Ahmad</td>
                  <td>Innova Zenix</td>
                  <td>2025-10-01</td>
                  <td><span class="status success">Selesai</span></td>
                </tr>
                <tr>
                  <td>#002</td>
                  <td>Rina</td>
                  <td>Avanza</td>
                  <td>2025-10-02</td>
                  <td><span class="status pending">Proses</span></td>
                </tr>
                <tr>
                  <td>#003</td>
                  <td>Doni</td>
                  <td>Fortuner</td>
                  <td>2025-10-03</td>
                  <td><span class="status cancel">Batal</span></td>
                </tr>
              </tbody>
            </table>
          </section>
        </main>
      </div>
</body>
</html>