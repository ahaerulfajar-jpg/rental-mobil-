<?php
session_start();
include('../app/config/database.php'); 

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php"); 
    exit;
}

// Query data maintenance
$query = "
SELECT 
  pm.id,
  m.nama_mobil,
  pm.jenis_perawatan,
  pm.tanggal_mulai,
  pm.biaya,
  pm.status_perawatan
FROM perawatan_mobil pm
JOIN mobil m ON pm.mobil_id = m.id
ORDER BY pm.tanggal_mulai DESC
";

$result = mysqli_query($conn, $query);

/* =========================
   SIMPAN MAINTENANCE
========================= */
if (isset($_POST['simpan_maintenance'])) {

    $mobil_id        = $_POST['mobil_id'];
    $jenis_perawatan = $_POST['jenis_perawatan'];
    $tanggal_mulai   = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? null;
    $biaya           = $_POST['biaya'] ?? 0;
    $bengkel         = $_POST['bengkel'] ?? '';
    $deskripsi       = $_POST['deskripsi'] ?? '';

    // Insert maintenance
    $insert = mysqli_query($conn, "
        INSERT INTO perawatan_mobil 
        (mobil_id, jenis_perawatan, tanggal_mulai, tanggal_selesai, biaya, bengkel, deskripsi, status_perawatan)
        VALUES (
          '$mobil_id',
          '$jenis_perawatan',
          '$tanggal_mulai',
          ".($tanggal_selesai ? "'$tanggal_selesai'" : "NULL").",
          '$biaya',
          '$bengkel',
          '$deskripsi',
          'dalam_perawatan'
        )
    ");

    if ($insert) {

        // Update status mobil
        mysqli_query($conn, "
            UPDATE mobil 
            SET status = 'Maintenance' 
            WHERE id = '$mobil_id'
        ");

        echo "<script>
            alert('Maintenance berhasil ditambahkan');
            window.location='perawatan.php';
        </script>";
    } else {
        echo "<script>alert('Gagal menyimpan maintenance');</script>";
    }
}



?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Mobil | Admin</title>
  <link rel="stylesheet" href="css/maintanance.css">
  <link rel="stylesheet" href="../app/admin/css/add_maintanance.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/a2e0a2c6f1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="dashboard-container">
     <aside class="sidebar">
        <div class="sidebar-header">
    <div class="logo">
        <img src="../img/logo2.png" alt="Logo Simpati Trans">
    </div>
</div>

<ul class="menu">
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li class="active"><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
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

 <main class="main-content">
  <!-- OVERLAY -->
<div class="overlay" id="overlayMaintenance" onclick="closeMaintenanceForm()"></div>

<!-- CARD TAMBAH MAINTENANCE -->
<div class="maintenance-popup" id="maintenancePopup">
  <div class="maintenance-card">

    <div class="card-header">
      <h5>Tambah Maintenance Mobil</h5>
      <button class="close-btn" onclick="closeMaintenanceForm()">×</button>
    </div>

    <form method="POST">

      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Mobil</label>
          <select name="mobil_id" class="form-control" required>
            <option value="">-- Pilih Mobil --</option>
            <?php
            $mobil = mysqli_query($conn, "SELECT id,nama_mobil FROM mobil WHERE status!='Maintenance'");
            while ($m = mysqli_fetch_assoc($mobil)):
            ?>
              <option value="<?= $m['id'] ?>"><?= $m['nama_mobil'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label>Jenis Perawatan</label>
          <select name="jenis_perawatan" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="Servis Berkala">Servis Berkala</option>
            <option value="Ganti Oli">Ganti Oli</option>
            <option value="Tune Up">Tune Up</option>
            <option value="Perbaikan Mesin">Perbaikan Mesin</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label>Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
          <label>Tanggal Selesai</label>
          <input type="date" name="tanggal_selesai" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
          <label>Biaya</label>
          <input type="number" name="biaya" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
          <label>Bengkel</label>
          <input type="text" name="bengkel" class="form-control">
        </div>

        <div class="col-md-12 mb-3">
          <label>Deskripsi</label>
          <textarea name="deskripsi" class="form-control"></textarea>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" name="simpan_maintenance" class="btn btn-success">
          Simpan
        </button>
        <button type="button" class="btn btn-secondary" onclick="closeMaintenanceForm()">
          Batal
        </button>
      </div>

    </form>

  </div>
</div>

    <div class="page-header">
      <button class="btn btn-primary" onclick="openMaintenanceForm()">
        <i class="fa fa-plus"></i> Tambah Maintenance
      </button>
    </div>

    <div class="options">
      <a href="datamobil.php" class="option-btn">Mobil</a>
      <a href="perawatan.php" class="option-btn active">Maintenance</a>
    </div>

    <div class="card-maintenance">
      <table class="table table-hover maintenance-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Mobil</th>
            <th>Jenis Maintenance</th>
            <th>Tanggal</th>
            <th>Biaya</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
            <td><?= htmlspecialchars($row['jenis_perawatan']) ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal_mulai'])) ?></td>
            <td>Rp <?= number_format($row['biaya'], 0, ',', '.') ?></td>
            <td>
              <span class="badge status-<?= $row['status_perawatan'] ?>">
                <?= ucfirst(str_replace('_',' ', $row['status_perawatan'])) ?>
              </span>
            </td>
            <td>
              <a href="detail_maintenance.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">
                <i class="fa fa-eye"></i>
              </a>
              <a href="edit_maintenance.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                <i class="fa fa-edit"></i>
              </a>
              <?php if ($row['status_perawatan'] == 'dalam_perawatan'): ?>
              <a href="../app/admin/selesai_maintenance.php?id=<?= $row['id'] ?>" 
                class="btn btn-sm btn-success"
                onclick="return confirm('Selesaikan maintenance ini?')">
                <i class="fa fa-check"></i>
              </a>
<?php endif; ?>

            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr>
            <td colspan="7" class="text-center">Data maintenance belum tersedia</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script src="js/datamobil.js"></script>

</body>
</html>