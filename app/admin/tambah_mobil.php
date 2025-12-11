<?php
include('../config/database.php');

if (isset($_POST['submit'])) {

    // Upload Gambar
    $gambar = $_FILES['gambar_mobil']['name'];
    $tmp = $_FILES['gambar_mobil']['tmp_name'];
    $folder = "../../img/";

    move_uploaded_file($tmp, $folder . $gambar);

    // Data Form
    $nama = $_POST['nama_mobil'];
    $tipe = $_POST['tipe_mobil'];
    $tahun = $_POST['tahun'];
    $kapasitas = $_POST['kapasitas'];
    $transmisi = $_POST['transmisi'];
    $bahan = $_POST['bahan_bakar'];
    $kursi = $_POST['kursi'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    $query = "INSERT INTO mobil (nama_mobil, tipe_mobil, tahun, kapasitas, transmisi, bahan_bakar, kursi, harga_sewa_per_hari, status, gambar_mobil)
              VALUES ('$nama','$tipe','$tahun','$kapasitas','$transmisi','$bahan','$kursi','$harga','$status','$gambar')";

    if ($conn->query($query)) {
        header("Location: ../../admin/datamobil.php?success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Mobil</title>
    <link rel="stylesheet" href="../../admin/css/form.css">
</head>
<body>

<div class="form-container">
    <h2>Tambah Mobil</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Nama Mobil</label>
        <input type="text" name="nama_mobil" required>

        <label>Tipe Mobil</label>
        <input type="text" name="tipe_mobil" required>

        <label>Tahun</label>
        <input type="number" name="tahun" required>

        <label>Kapasitas</label>
        <input type="number" name="kapasitas" required>

        <label>Transmisi</label>
        <select name="transmisi">
            <option>Manual</option>
            <option>Automatic</option>
            <option>Manual/Automatic</option>
            <option>CVT</option>
        </select>

        <label>Bahan Bakar</label>
        <select name="bahan_bakar">
            <option>Bensin</option>
            <option>Solar</option>
        </select>

        <label>Harga Sewa / Hari</label>
        <input type="number" name="harga" required>

        <label>Status</label>
        <select name="status">
            <option value="Tersedia">Tersedia</option>
            <option value="Disewa">Disewa</option>
        </select>

        <label>Gambar Mobil</label>
        <input type="file" name="gambar_mobil" required>

        <button type="submit" name="submit">Simpan</button>

    </form>
</div>

</body>
</html>
