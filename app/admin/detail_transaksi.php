<?php
session_start();
include('../config/database.php');

if (!isset($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = $_GET['id'];

$query = "
    SELECT t.*, m.nama_mobil, m.gambar_mobil, m.harga_sewa_per_hari,
           s.nama AS nama_sopir, s.telepon AS telepon_sopir
    FROM transaksi t
    LEFT JOIN mobil m ON t.mobil_id = m.id
    LEFT JOIN sopir s ON t.sopir_id = s.id
    WHERE t.id = '$id'
";

$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("Transaksi tidak ditemukan.");
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Transaksi</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../../admin/css/detail_transaksi.css">
</head>

<body>

<div class="detail-container">

    <div class="card">
        <div class="left">
            <img src="../../img/<?= $data['gambar_mobil']; ?>" class="car-image" alt="">
        </div>

        <div class="right">
            <h1><?= $data['nama_mobil']; ?></h1>

            <h3>Detail Transaksi</h3>

            <div class="spec-grid">
                <div class="spec-box">
                    <i class="fa-solid fa-user"></i>
                    <p class="label">Nama Pelanggan</p>
                    <p class="value"><?= $data['nama_pelanggan']; ?></p>
                </div>

                <div class="spec-box">
                    <i class="fa-solid fa-envelope"></i>
                    <p class="label">Email</p>
                    <p class="value"><?= $data['email']; ?></p>
                </div>

                <div class="spec-box">
                    <i class="fa-solid fa-calendar"></i>
                    <p class="label">Tanggal Mulai</p>
                    <p class="value"><?= $data['tanggal_mulai']; ?></p>
                </div>

                <div class="spec-box">
                    <i class="fa-solid fa-calendar-check"></i>
                    <p class="label">Tanggal Selesai</p>
                    <p class="value"><?= $data['tanggal_selesai']; ?></p>
                </div>

                <div class="spec-box">
                    <i class="fa-solid fa-phone"></i>
                    <p class="label">Telepon</p>
                    <p class="value"><?= $data['telepon']; ?></p>
                </div>

                <!-- Tambahan: Kolom Pakai Sopir? -->
                <div class="spec-box">
                    <i class="fa-solid fa-id-card"></i>
                    <p class="label">Pakai Sopir?</p>
                    <p class="value">
                        <?php if ($data['pakai_sopir'] == "ya"): ?>
                            Ya
                        <?php else: ?>
                            Tidak
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Kolom Detail Sopir -->
                <div class="spec-box">
                    <i class="fa-solid fa-user-tie"></i>
                    <p class="label">Detail Sopir</p>
                    <p class="value">
                        <?php if ($data['pakai_sopir'] == "ya" && !empty($data['sopir_id'])): ?>
                            <?= $data['nama_sopir']; ?> <br>
                            <small style="color:gray;">Telp: <?= $data['telepon_sopir']; ?></small>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </p>
                </div>

                <div class="spec-box">
                    <i class="fa-solid fa-clock"></i>
                    <p class="label">Jam Mulai</p>
                    <p class="value"><?= date("H:i", strtotime($data['jam_mulai'])); ?></p>
                </div>


                <div class="spec-box full">
                    <i class="fa-solid fa-location-dot"></i>
                    <p class="label">Alamat Jemput</p>
                    <p class="value"><?= nl2br($data['alamat_jemput']); ?></p>
                </div>

                <div class="spec-box full">
                    <i class="fa-solid fa-note-sticky"></i>
                    <p class="label">Catatan</p>
                    <p class="value"><?= nl2br($data['catatan']); ?></p>
                </div>

                <div class="spec-box">
                    <i class="fa-solid fa-money-bill"></i>
                    <p class="label">Total Harga</p>
                    <p class="value price">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></p>
                </div>

                <div class="spec-box">
                    <i class="fa-solid fa-circle-info"></i>
                    <p class="label">Status</p>
                    <p class="value status <?= strtolower($data['status']); ?>"><?= $data['status']; ?></p>
                </div>
            </div>

            <!-- BUTTONS -->
        <div class="button-row">
            <?php if ($data['status'] == "Menunggu"): ?>
                <a href="aksi_transaksi.php?id=<?= $data['id']; ?>&action=setuju"
                class="btn approve"
                onclick="return confirm('Setujui transaksi ini?')">
                <i class="fa-solid fa-check"></i> Konfirmasi
                </a>

                <a href="aksi_transaksi.php?id=<?= $data['id']; ?>&action=tolak"
                class="btn delete"
                onclick="return confirm('Tolak transaksi ini?')">
                <i class="fa-solid fa-xmark"></i> Tolak
                </a>
            <?php endif; ?>

            <?php if ($data['status'] == "Disetujui"): ?>
                <a href="aksi_transaksi.php?id=<?= $data['id']; ?>&action=selesai"
                class="btn complete"
                onclick="return confirm('Tandai transaksi sebagai selesai?')">
                <i class="fa-solid fa-check-circle"></i> Selesai
                </a>
            <?php endif; ?>

            <a href="hapus_transaksi.php?id=<?= $data['id']; ?>"
            class="btn delete"
            onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
            <i class="fa-solid fa-trash"></i> Hapus
            </a>

            <a href="transaksi.php" class="btn back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>


        </div>
    </div>

</div>

</body>
</html>