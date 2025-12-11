<?php
include('../app/config/database.php');
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
            <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
            <li class="active"><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          </ul>
        </aside>


  <!-- MAIN CONTENT -->
  <main class="main-content">
    <div class="header">
      <h1>Kelola Admin</h1>
      <a href="../app/admin/tambah_admin.php" class="btn-add"><i class="fa-solid fa-plus"></i> Tambah Admin</a>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Dibuat Pada</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
            <td class="action-buttons">
              <a href="../app/admin/edit_admin.php?id=<?= $row['id']; ?>" class="btn-action edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
              <a href="../app/admin/reset_password.php?id=<?= $row['id']; ?>" class="btn-action reset" title="Reset Password"><i class="fa-solid fa-key"></i></a>
              <a href="../app/admin/hapus_admin.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus admin ini?')" class="btn-action delete" title="Hapus"><i class="fa-solid fa-trash"></i></a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>

</body>
</html>
