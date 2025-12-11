<?php
session_start();
include "../app/config/database.php";

$sopir = $conn->query("SELECT * FROM sopir ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sopir - Simpati Trans</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/sopir.css">
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
            <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li class="active"><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
            <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          </ul>
        </aside>

<div class="page">
    <div class="page-header">
        <a href="../app/admin/tambah_sopir.php" class="btn-add">
            <i class="fa-solid fa-plus"></i> Tambah Sopir
        </a>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Sopir</th>
                    <th>No. Telepon</th>
                    <th>Status</th>
                    <th>Harga/Hari</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; while($row = $sopir->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>

                    <td>
                        <img src="../img/<?= $row['foto']; ?>" class="foto">
                    </td>

                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['telepon']; ?></td>

                    <td>
                        <?php if($row['status'] == "tersedia"): ?>
                            <span class="badge bg-green">Tersedia</span>
                        <?php elseif($row['status'] == "dipesan"): ?>
                            <span class="badge bg-orange">Dipesan</span>
                        <?php else: ?>
                            <span class="badge bg-red">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>

                    <td>Rp <?= number_format($row['harga_per_hari'], 0, ',', '.'); ?></td>

                    <td class="action-buttons">
                        <a href="detail_sopir.php?id=<?= $row['id']; ?>" class="btn-action blue">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="edit_sopir.php?id=<?= $row['id']; ?>" class="btn-action green">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <a href="hapus_sopir.php?id=<?= $row['id']; ?>" 
                           onclick="return confirm('Hapus sopir ini?')"
                           class="btn-action red">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
