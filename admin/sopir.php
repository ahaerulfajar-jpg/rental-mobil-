<?php
session_start();
include "../app/config/database.php";

// Pengecekan login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$sopir = $conn->query("SELECT * FROM sopir ORDER BY id DESC");

/* ===============================
   PROSES TAMBAH SOPIR
================================ */
if (isset($_POST['simpan_sopir'])) {

    $nama   = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $email  = $_POST['email'];
    $alamat = $_POST['alamat'];
    $status = $_POST['status'];
    $harga  = $_POST['harga_per_hari'];
    $tanggal = $_POST['tanggal_bergabung'];

    // Upload foto
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../img/" . $foto);
    }

    $query = "
        INSERT INTO sopir 
        (nama, telepon, email, alamat, status, harga_per_hari, tanggal_bergabung, foto)
        VALUES 
        ('$nama','$telepon','$email','$alamat','$status','$harga','$tanggal','$foto')
    ";

    mysqli_query($conn, $query);

    header("Location: sopir.php");
    exit;
}

/* ===============================
   PROSES EDIT SOPIR
================================ */
if (isset($_POST['update_sopir'])) {

    $id     = $_POST['id'];
    $nama   = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $email  = $_POST['email'];
    $alamat = $_POST['alamat'];
    $status = $_POST['status'];
    $harga  = $_POST['harga_per_hari'];
    $tanggal = $_POST['tanggal_bergabung'];

    // Ambil foto lama
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto FROM sopir WHERE id='$id'"));
    $foto_lama = $data['foto'];

    // Upload foto baru (jika ada)
    if (!empty($_FILES['foto']['name'])) {
        $foto_baru = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../img/" . $foto_baru);
    } else {
        $foto_baru = $foto_lama;
    }

    $query = "
        UPDATE sopir SET
            nama='$nama',
            telepon='$telepon',
            email='$email',
            alamat='$alamat',
            status='$status',
            harga_per_hari='$harga',
            tanggal_bergabung='$tanggal',
            foto='$foto_baru'
        WHERE id='$id'
    ";

    mysqli_query($conn, $query);

    header("Location: sopir.php");
    exit;
}


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
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li><a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="datamobil.php"><i class="fa-solid fa-car"></i> Daftar Mobil</a></li>
            <li><a href="transaksi.php"><i class="fa-solid fa-handshake"></i> Transaksi</a></li>
            <li class="active"><a href="sopir.php"><i class="fa-solid fa-id-card"></i> Sopir</a></li>
            <li><a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a></li>
            <li><a href="profil.php"><i class="fa-solid fa-person"></i> Profil</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
          <?php endif; ?>
           
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
              <li class="active"><a href="dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
              <li><a href="monitoring.php"><i class="fa-solid fa-eye"></i> Monitoring </a></li>
              <li><a href="laporan.php"><i class="fa-solid fa-chart-line"></i> Laporan</a></li>
              <li><a href="dataadmin.php"><i class="fa-solid fa-user-gear"></i> Admin</a></li>
              <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            <?php endif; ?>
    
          </ul>
        </aside>

<div class="page">
    <div class="page-header">
        <a href="javascript:void(0)" class="btn-add" onclick="openTambahSopir()">
            <i class="fa-solid fa-plus"></i> Tambah Sopir
        </a>
    </div>

    <!-- Overlay Detail Sopir -->
<div id="overlayDetail" class="overlay">
    <div class="detail-card">
        <span class="close-btn" onclick="closeDetail()">&times;</span>

        <div class="detail-header">
            <img id="detailFoto" src="" class="detail-foto">
            <h2 id="detailNama"></h2>
            <span id="detailStatus" class="badge"></span>
        </div>

        <div class="detail-body">
            <p><strong>No. Telepon:</strong> <span id="detailTelepon"></span></p>
            <p><strong>Harga / Hari:</strong> <span id="detailHarga"></span></p>
            <p><strong>Email:</strong> <span id="detailEmail"></span></p>
            <p><strong>Alamat:</strong> <span id="detailAlamat"></span></p>
            <p><strong>Tanggal Bergabung:</strong> <span id="detailTanggal"></span></p>
        </div>
    </div>
</div>


<!-- Overlay Form Sopir (Tambah & Edit) -->
<div id="overlaySopir" class="overlay">
    <div class="detail-card">
        <span class="close-btn" onclick="closeSopirForm()">&times;</span>

        <h2 id="formTitle">Tambah Sopir</h2>

        <form id="formSopir" method="POST" enctype="multipart/form-data">

            <!-- ID (dipakai saat edit) -->
            <input type="hidden" name="id" id="sopirId">

            <div style="text-align:center; margin-bottom:15px;">
                <img id="fotoPreview" src="../img/default.png" class="detail-foto">
            </div>

            <label>Nama Sopir</label>
            <input type="text" name="nama" id="nama" required>

            <label>No. Telepon</label>
            <input type="text" name="telepon" id="telepon" required>

            <label>Email</label>
            <input type="email" name="email" id="email">

            <label>Alamat</label>
            <textarea name="alamat" id="alamat"></textarea>

            <label>Status</label>
            <select name="status" id="status">
                <option value="tersedia">Tersedia</option>
                <option value="dipesan">Dipesan</option>
                <option value="tidak_aktif">Tidak Aktif</option>
            </select>

            <label>Harga / Hari</label>
            <input type="number" name="harga_per_hari" id="harga" value="100000">

            <label>Tanggal Bergabung</label>
            <input type="date" name="tanggal_bergabung" id="tanggal">

            <label>Foto Sopir</label>
            <input type="file" name="foto">

            <button type="submit" id="btnSubmit" name="simpan_sopir" class="btn-save">
                Simpan
            </button>
        </form>
    </div>
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
                        <a href="javascript:void(0)"
                            class="btn-action blue"
                            onclick="showDetail(this)"
                            data-nama="<?= $row['nama']; ?>"
                            data-telepon="<?= $row['telepon']; ?>"
                            data-status="<?= $row['status']; ?>"
                            data-harga="<?= number_format($row['harga_per_hari'], 0, ',', '.'); ?>"
                            data-email="<?= $row['email']; ?>"
                            data-alamat="<?= $row['alamat']; ?>"
                            data-tanggal="<?= $row['tanggal_bergabung']; ?>"
                            data-foto="../img/<?= $row['foto']; ?>">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                       <a href="javascript:void(0)" 
                            class="btn-action green"
                            onclick="openEditSopir(
                                '<?= $row['id']; ?>',
                                '<?= $row['nama']; ?>',
                                '<?= $row['telepon']; ?>',
                                '<?= $row['email']; ?>',
                                '<?= $row['alamat']; ?>',
                                '<?= $row['status']; ?>',
                                '<?= $row['harga_per_hari']; ?>',
                                '<?= $row['tanggal_bergabung']; ?>',
                                '<?= $row['foto']; ?>'
                            )">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <a href="../app/admin/hapus_sopir.php?id=<?= $row['id']; ?>" 
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

<script src="js/dataadmin.js"></script>
</body>
</html>
