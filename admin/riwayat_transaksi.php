<?php
session_start();
include('../app/config/database.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php"); 
    exit;
}

$query = "
    SELECT 
        t.id,
        t.tanggal_mulai,
        t.tanggal_selesai,
        t.total_harga,
        t.status,
        t.pakai_sopir,
        p.nama AS nama_pelanggan,
        m.nama_mobil,
        s.nama AS nama_sopir
    FROM transaksi t
    JOIN pelanggan p ON t.pelanggan_id = p.id
    JOIN mobil m ON t.mobil_id = m.id
    LEFT JOIN sopir s ON t.sopir_id = s.id
    ORDER BY t.created_at DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Mobil | Admin</title>
  <link rel="stylesheet" href="css/riwayat.css">
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
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li class="active"><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
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

 <div class="main-content">

    <!-- FILTER -->
    <div class="filter-box">
        <input type="date">
        <input type="text" placeholder="Cari transaksi...">
        <button class="btn-filter">Filter</button>
    </div>

 <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Mobil</th>
                    <th>Sopir</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_pelanggan']; ?></td>
                    <td><?= $row['nama_mobil']; ?></td>
                    <td>
                        <?= ($row['pakai_sopir'] == 'ya') 
                            ? ($row['nama_sopir'] ?? '-') 
                            : 'Tanpa Sopir'; ?>
                    </td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal_mulai'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal_selesai'])); ?></td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>