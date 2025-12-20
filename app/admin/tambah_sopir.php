<?php
session_start();
include "../config/database.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Sopir</title>
<link rel="stylesheet" href="css/formsopir.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<a href="proses_tambah_sopir.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali</a>

<div class="form-card">

    <h2 class="title"><i class="fa-solid fa-id-card"></i> Tambah Sopir</h2>

    <form action="proses_tambah_sopir.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label><i class="fa-solid fa-user"></i> Nama Sopir</label>
            <input type="text" name="nama" placeholder="Masukkan nama sopir" required>
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-phone"></i> No. Telepon</label>
            <input type="text" name="telepon" placeholder="08xxxxxxxxxx" required>
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-money-bill-wave"></i> Harga per Hari</label>
            <input type="number" name="harga" placeholder="Rp / hari" required>
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-image"></i> Foto Sopir</label>

            <div class="upload-area">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Upload Foto Sopir</p>
                <span>Format jpg, jpeg, png</span>
                <input type="file" name="foto" accept="../../img/" required>
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-check"></i> Simpan Data Sopir
        </button>

    </form>
</div>

</body>
</html>
