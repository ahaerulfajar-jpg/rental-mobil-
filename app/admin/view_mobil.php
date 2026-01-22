<?php
include('../config/database.php');

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM mobil WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Mobil - <?= $data['nama_mobil']; ?></title>
    <link rel="stylesheet" href="../../admin/css/viewmobil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="container-detail">

    <!-- Left Side (Image) -->
    <div class="image-box">
        <img src="../../img/<?= $data['gambar_mobil']; ?>" class="mobil-img">
    </div>

    <!-- Right Side (Details) -->
    <div class="info-box">
        
        <h1 class="title"><?= $data['nama_mobil']; ?></h1>

        <h3 class="sub-title">Spesifikasi :</h3>

        <div class="spec-grid">

            <div class="spec-item">
                <i class="fa-solid fa-wheelchair"></i>
                <div>
                    <p>TEMPAT DUDUK</p>
                    <span><?= $data['kapasitas']; ?></span>
                </div>
            </div>

            <div class="spec-item">
                <i class="fa-solid fa-suitcase"></i>
                <div>
                    <p>BAGASI</p>
                    <span><?= $data['bagasi'] ?? '1'; ?></span>
                </div>
            </div>

            <div class="spec-item">
                <i class="fa-solid fa-gears"></i>
                <div>
                    <p>TRANSMISI</p>
                    <span><?= strtoupper($data['transmisi']); ?></span>
                </div>
            </div>

            <div class="spec-item">
                <i class="fa-solid fa-gas-pump"></i>
                <div>
                    <p>BAHAN BAKAR</p>
                    <span><?= $data['bahan_bakar']; ?></span>
                </div>
            </div>

            <div class="spec-item">
                <i class="fa-solid fa-shield-alt"></i>
                <div>
                    <p>ASURANSI</p>
                    <span><?= $data['asuransi'] ?? 'YES'; ?></span>
                </div>
            </div>

            <div class="spec-item">
                <i class="fa-solid fa-user-tie"></i>
                <div>
                    <p>PENGEMUDI</p>
                    <span><?= $data['pengemudi'] ?? 'YES'; ?></span>
                </div>
            </div>

        </div>

        <p class="keterangan">
            <i class="fa-solid fa-shield-virus icon"></i>
            Kendaraan dan pengemudi telah diverifikasi dan mengikuti standar kebersihan serta swab berkala.
        </p>

        <a href="../../admin/datamobil.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>

    </div>

</div>

</body>
</html>
