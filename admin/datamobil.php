<?php
session_start();
include('../app/config/database.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php"); 
    exit;
}

$result = $conn->query("SELECT * FROM mobil ORDER BY id DESC");

if (isset($_POST['submit'])) {

    $gambar = $_FILES['gambar_mobil']['name'];
    $tmp = $_FILES['gambar_mobil']['tmp_name'];
    move_uploaded_file($tmp, "../img/" . $gambar);

    $query = "INSERT INTO mobil 
    (nama_mobil, tipe_mobil, tahun, kapasitas, transmisi, bahan_bakar, harga_sewa_per_hari, status, gambar_mobil)
    VALUES (
      '{$_POST['nama_mobil']}',
      '{$_POST['tipe_mobil']}',
      '{$_POST['tahun']}',
      '{$_POST['kapasitas']}',
      '{$_POST['transmisi']}',
      '{$_POST['bahan_bakar']}',
      '{$_POST['harga']}',
      '{$_POST['status']}',
      '$gambar'
    )";

    mysqli_query($conn, $query);
    header("Location: datamobil.php");
}

// Handle update
if (isset($_POST['update'])) {

    $id = $_POST['id'];

    if ($_FILES['gambar_mobil']['name'] != "") {
        $gambar = $_FILES['gambar_mobil']['name'];
        move_uploaded_file($_FILES['gambar_mobil']['tmp_name'], "../img/".$gambar);
    } else {
        $gambar = $conn->query("SELECT gambar_mobil FROM mobil WHERE id='$id'")
                       ->fetch_assoc()['gambar_mobil'];
    }

    $conn->query("UPDATE mobil SET
      nama_mobil='{$_POST['nama_mobil']}',
      tipe_mobil='{$_POST['tipe_mobil']}',
      tahun='{$_POST['tahun']}',
      kapasitas='{$_POST['kapasitas']}',
      transmisi='{$_POST['transmisi']}',
      bahan_bakar='{$_POST['bahan_bakar']}',
      harga_sewa_per_hari='{$_POST['harga']}',
      status='{$_POST['status']}',
      gambar_mobil='$gambar'
      WHERE id='$id'
    ");

    header("Location: datamobil.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Mobil | Admin</title>
  <link rel="stylesheet" href="css/datamobil.css">
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

<!-- MAIN CONTENT -->
<main class="main-content">

    <!-- OVERLAY TAMBAH MOBIL -->
    <div class="overlay" id="modalTambahMobil">
    <div class="modal-card">

        <div class="modal-header">
        <h3>Tambah Mobil</h3>
        <button onclick="closeTambahMobil()">&times;</button>
        </div>

    <form method="POST" enctype="multipart/form-data" class="form-grid">

    <div class="form-group">
        <label>Nama Mobil</label>
        <input type="text" name="nama_mobil" required>
    </div>

    <div class="form-group">
        <label>Tipe Mobil</label>
        <input type="text" name="tipe_mobil" required>
    </div>

    <div class="form-group">
        <label>Tahun</label>
        <input type="number" name="tahun" required>
    </div>

    <div class="form-group">
        <label>Kapasitas</label>
        <input type="number" name="kapasitas" required>
    </div>

    <div class="form-group">
        <label>Transmisi</label>
        <select name="transmisi">
        <option>Manual</option>
        <option>Automatic</option>
        <option>CVT</option>
        </select>
    </div>

    <div class="form-group">
        <label>Bahan Bakar</label>
        <select name="bahan_bakar">
        <option>Bensin</option>
        <option>Solar</option>
        </select>
    </div>

    <div class="form-group">
        <label>Harga / Hari</label>
        <input type="number" name="harga" required>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status">
        <option value="Tersedia">Tersedia</option>
        <option value="Disewa">Disewa</option>
        </select>
    </div>

    <!-- FULL WIDTH -->
    <div class="form-group full">
        <label>Gambar Mobil</label>
        <input type="file" name="gambar_mobil" required>
    </div>

    <div class="form-group full">
        <button type="submit" name="submit" class="btn-save">
        Simpan Mobil
        </button>
    </div>

    </form>

  </div>
</div>

    <div class="header">
        <button class="btn-add" onclick="openTambahMobil()">
            <i class="fa-solid fa-plus"></i> Tambah Mobil
        </button>

    </div>

      <!-- Tambahan: Opsi Filter -->
      <div class="options">
            <a href="monitoring.php" class="option-btn active">Mobil</a>
            <a href="perawatan.php" class="option-btn">Maintanace</a>
        </div>

    <div class="grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="card">

            <div class="img-wrapper">
                <img src="../img/<?= $row['gambar_mobil']; ?>" alt="<?= $row['nama_mobil']; ?>">
                <span class="badge"><?= $row['status']; ?></span>
                <span class="price">Rp <?= number_format($row['harga_sewa_per_hari'],0,',','.'); ?> / hari</span>
            </div>

            <h3><?= $row['nama_mobil']; ?></h3>

            <div class="specs">
                <p><i class="fa-solid fa-wheelchair"></i></i> <?= $row['kapasitas']; ?> Orang</p>
                <p><i class="fa-solid fa-car-side"></i> <?= $row['transmisi']; ?></p>
                <p><i class="fa-solid fa-gas-pump"></i> <?= $row['bahan_bakar']; ?></p>
                <p><i class="fa-solid fa-calendar"></i> <?= $row['tahun']; ?></p>
            </div>

            <div class="actions">
            <button class="btn edit" onclick="openEditMobil(<?= $row['id']; ?>)">
                <i class="fa-solid fa-pen"></i>
            </button>
                <a href="../app/admin/view_mobil.php?id=<?= $row['id']; ?>" class="btn view">
                    <i class="fa-solid fa-eye"></i>
                </a>

                <a href="../app/admin/hapus_mobil.php?id=<?= $row['id']; ?>" class="btn delete"
                   onclick="return confirm('Yakin ingin menghapus mobil ini?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </div>

        </div>
        <?php } ?>
    </div>

     <!-- OVERLAY EDIT MOBIL -->
    <div class="overlay" id="modalEditMobil">
        <div class="modal-card">
    <div class="modal-header">
        <h3>Edit Mobil</h3>
        <button onclick="closeEditMobil()">&times;</button>
    </div>

            <form method="POST" enctype="multipart/form-data" class="form-grid">

            <input type="hidden" name="id" id="edit_id">

            <div class="form-group">
                <label>Nama Mobil</label>
                <input type="text" name="nama_mobil" id="edit_nama" required>
            </div>

            <div class="form-group">
                <label>Tipe Mobil</label>
                <input type="text" name="tipe_mobil" id="edit_tipe" required>
            </div>

            <div class="form-group">
                <label>Tahun</label>
                <input type="number" name="tahun" id="edit_tahun" required>
            </div>

            <div class="form-group">
                <label>Kapasitas</label>
                <input type="number" name="kapasitas" id="edit_kapasitas" required>
            </div>

            <div class="form-group">
                <label>Transmisi</label>
                <select name="transmisi" id="edit_transmisi">
                <option>Manual</option>
                <option>Automatic</option>
                <option>CVT</option>
                </select>
            </div>

            <div class="form-group">
                <label>Bahan Bakar</label>
                <select name="bahan_bakar" id="edit_bahan">
                <option>Bensin</option>
                <option>Solar</option>
                </select>
            </div>

            <div class="form-group">
                <label>Harga / Hari</label>
                <input type="number" name="harga" id="edit_harga" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status">
                <option value="Tersedia">Tersedia</option>
                <option value="Disewa">Disewa</option>
                </select>
            </div>

            <div class="form-group full">
                <label>Gambar Mobil</label>
                <input type="file" name="gambar_mobil">
                <img id="preview_gambar" width="120" style="margin-top:8px">
            </div>

            <div class="form-group full">
                <button type="submit" name="update" class="btn-save">
                Update Mobil
                </button>
            </div>

            </form>
    </div>
</div>
</main>

<script src="js/datamobil.js"></script>
</body>
</html>
