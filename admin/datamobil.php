<?php
session_start();
include('../app/config/database.php');

// Pengecekan login
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM mobil ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Mobil | Admin</title>
  <link rel="stylesheet" href="css/datamobil.css">
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
    <li><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
    <li class="active"><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
    <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
    <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
    <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
    <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
    <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
</ul>
 </aside>

<!-- MAIN CONTENT -->
<main class="main-content">
    <div class="header">
        <a href="../app/admin/tambah_mobil.php" class="btn-add">
            <i class="fa-solid fa-plus"></i> Tambah Mobil
        </a>
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
                <a href="../app/admin/edit_mobil.php?id=<?= $row['id']; ?>" class="btn edit">
                    <i class="fa-solid fa-pen"></i>
                </a>

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

</main>

</body>
</html>
