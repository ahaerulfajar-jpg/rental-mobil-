<?php
session_start();
include('../app/config/database.php');

// Cek akses: Hanya pemilik yang bisa akses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pemilik') {
  header("Location: index.php"); // Redirect jika bukan pemilik
  exit;
}

$result = $conn->query("
    SELECT id, username, email, created_at
    FROM users
    WHERE role = 'admin'
    ORDER BY created_at DESC
");

$result = $conn->query("SELECT * FROM users WHERE role='admin' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Admin | Simpati Trans</title>
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a2e0a2c6f1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

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
            <li><a href="perawatan.php"><i class="fa-solid fa-screwdriver-wrench"></i> Perawatan Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li class="active"><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
              <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            <?php endif; ?>

          </ul>
    </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content">
    <div class="header">
      <h1>Kelola Admin</h1>
      <button class="btn-add" onclick="openAddAdmin()">
        <i class="fa-solid fa-plus"></i> Tambah Admin
      </button>
    </div>

    <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Status</th>
          <th>Dibuat Pada</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php $no = 1; ?>
        <?php while ($row = $result->fetch_assoc()): ?>

        <tr>
          <td><?= $no++; ?></td>

          <td><?= htmlspecialchars($row['username']); ?></td>

          <td><?= htmlspecialchars($row['email']); ?></td>

          <!-- STATUS -->
          <td>
            <span class="badge badge-aktif">Aktif</span>
          </td>

          <td><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>

          <td class="action-buttons">
            <!-- VIEW AKTIVITAS (OVERLAY) -->
            <button
              class="btn-action view"
              title="Lihat Aktivitas"
              onclick="openAktivitasAdmin(<?= $row['id']; ?>)">
              <i class="fa-solid fa-eye"></i>
            </button>

            <!-- HAPUS -->
            <a
              href="../app/admin/hapus_admin.php?id=<?= $row['id']; ?>"
              onclick="return confirm('Yakin ingin menghapus admin ini?')"
              class="btn-action delete"
              title="Hapus">
              <i class="fa-solid fa-trash"></i>
            </a>
          </td>
        </tr>

        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" style="text-align:center;">
            Data admin belum tersedia
          </td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
    
    </div>
  </main>

  <!-- OVERLAY TAMBAH ADMIN -->
<div id="addAdminOverlay" class="overlay">
  <div class="overlay-card">
    <h3><i class="fa-solid fa-user-plus"></i> Tambah Admin</h3>

    <form action="../app/admin/proses_tambah_admin.php" method="POST">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>

      <input type="hidden" name="role" value="admin">

      <div class="form-actions">
        <button type="submit" class="btn-save">
          <i class="fa-solid fa-floppy-disk"></i> Simpan
        </button>
        <button type="button" class="btn-cancel" onclick="closeAddAdmin()">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

<!-- overlay view admin -->
<div id="overlayAktivitas" class="overlay hidden">
  <div class="overlay-card">
    <div class="overlay-header">
      <h3>📊 Aktivitas Admin</h3>
      <button onclick="closeAktivitas()">✖</button>
    </div>

    <div class="overlay-body" id="aktivitasContent">
      <p>Memuat data...</p>
    </div>
  </div>
</div>


<script src="js/dataadmin.js"></script>

</body>
</html>
