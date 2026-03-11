<?php
session_start();
include('../app/config/database.php');
include('../app/config/app.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$mobil_id = isset($_GET['mobil_id']) ? (int) $_GET['mobil_id'] : 0;
$mobil = null;
$galeri = [];
$kategori_label = ['eksterior' => 'Eksterior', 'interior' => 'Interior', 'mesin' => 'Mesin', 'lainnya' => 'Lainnya'];

if ($mobil_id > 0) {
    $stmt = $conn->prepare("SELECT id, nama_mobil FROM mobil WHERE id = ?");
    $stmt->bind_param('i', $mobil_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        header('Location: galeri_mobil.php');
        exit; 
    }
    $mobil = $res->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("SELECT id, mobil_id, kategori, nama_file, keterangan, url_gambar FROM mobil_gambar WHERE mobil_id = ? ORDER BY kategori, id");
    $stmt->bind_param('i', $mobil_id);
    $stmt->execute();
    $galeri = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $result_mobil = $conn->query("SELECT id, nama_mobil FROM mobil ORDER BY nama_mobil ASC");
}

function img_src($row, $mobil_id) {
    if (!empty($row['url_gambar'])) {
        return htmlspecialchars($row['url_gambar']);
    }
    return (defined('BASE_URL') ? BASE_URL : '') . '/img/mobil/' . (int)$mobil_id . '/' . htmlspecialchars($row['nama_file']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeri Mobil | Admin</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .galeri-list { display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    .galeri-list .card-img { width: 120px; height: 90px; object-fit: cover; border-radius: 8px; }
    .galeri-item { display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px; margin-bottom: 0.5rem; }
    .galeri-item .img-wrap { flex-shrink: 0; }
    .galeri-section { margin-bottom: 2rem; }
    .galeri-section h4 { margin-bottom: 1rem; color: #052a6e; }
    .btn-upload { margin-bottom: 1.5rem; }
  </style>
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
        <li class="active"><a href="galeri_mobil.php"><i class="fa-solid fa-images"></i> Galeri Mobil</a></li>
        <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
        <li><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
        <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
        <li><a href="profil.php"><i class="fa-solid fa-person"></i> Profil</a></li>
        <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
      <?php endif; ?>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
        <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
        <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring</a></li>
        <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
        <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
        <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
      <?php endif; ?>
    </ul>
  </aside>

  <main class="main-content">
    <header class="header">
      <h1><?= $mobil_id ? 'Galeri: ' . htmlspecialchars($mobil['nama_mobil']) : 'Galeri Mobil'; ?></h1>
    </header>

    <?php if (!$mobil_id): ?>
      <p class="mb-3">Pilih mobil untuk mengelola galeri gambar (eksterior, interior, mesin, lainnya).</p>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Mobil</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result_mobil->fetch_assoc()): ?>
              <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama_mobil']) ?></td>
                <td>
                  <a href="galeri_mobil.php?mobil_id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-images"></i> Kelola Galeri
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="mb-3">
        <a href="galeri_mobil.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Mobil</a>
      </p>

      <div class="btn-upload">
        <form method="POST" action="../app/admin/proses_upload_gambar_mobil.php" enctype="multipart/form-data" class="row g-3 align-items-end">
          <input type="hidden" name="mobil_id" value="<?= (int)$mobil_id ?>">
          <div class="col-auto">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
              <?php foreach ($kategori_label as $val => $label): ?>
                <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-auto">
            <label class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Opsional" maxlength="255">
          </div>
          <div class="col-auto">
            <label class="form-label">Gambar</label>
            <input type="file" name="gambar" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" required>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload</button>
          </div>
        </form>
      </div>

      <?php
      $by_kategori = [];
      foreach ($galeri as $row) {
        $by_kategori[$row['kategori']][] = $row;
      }
      foreach ($kategori_label as $key => $label):
        $items = isset($by_kategori[$key]) ? $by_kategori[$key] : [];
        if (empty($items)) continue;
      ?>
      <div class="galeri-section">
        <h4><?= htmlspecialchars($label) ?></h4>
        <div class="galeri-list">
          <?php foreach ($items as $row): ?>
            <div class="galeri-item">
              <div class="img-wrap">
                <img src="<?= img_src($row, $mobil_id) ?>" alt="<?= htmlspecialchars($row['keterangan'] ?? $row['nama_file']) ?>" class="card-img">
              </div>
              <div class="flex-grow-1">
                <?php if (!empty($row['keterangan'])): ?>
                  <div class="small text-muted"><?= htmlspecialchars($row['keterangan']) ?></div>
                <?php endif; ?>
                <div class="small"><?= htmlspecialchars($row['nama_file']) ?></div>
              </div>
              <div>
                <a href="../app/admin/hapus_gambar_mobil.php?id=<?= (int)$row['id'] ?>&mobil_id=<?= (int)$mobil_id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus gambar ini?');">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if (empty($galeri)): ?>
        <p class="text-muted">Belum ada gambar. Upload gambar di atas.</p>
      <?php endif; ?>
    <?php endif; ?>
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
