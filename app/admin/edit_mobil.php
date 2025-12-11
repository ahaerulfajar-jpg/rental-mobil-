<?php
include('../config/database.php');

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM mobil WHERE id=$id")->fetch_assoc();

if (isset($_POST['submit'])) {
    
    $nama = $_POST['nama_mobil'];
    $tipe = $_POST['tipe_mobil'];
    $tahun = $_POST['tahun'];
    $kapasitas = $_POST['kapasitas'];
    $transmisi = $_POST['transmisi'];
    $bahan = $_POST['bahan_bakar'];
    $kursi = $_POST['kursi'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    // Jika gambar diubah
    if ($_FILES['gambar_mobil']['name'] != "") {
        $gambar = $_FILES['gambar_mobil']['name'];
        move_uploaded_file($_FILES['gambar_mobil']['tmp_name'], "../../img/" . $gambar);
    } else {
        $gambar = $data['gambar_mobil'];
    }

    $query = "UPDATE mobil SET 
                nama_mobil='$nama',
                tipe_mobil='$tipe',
                tahun='$tahun',
                kapasitas='$kapasitas',
                transmisi='$transmisi',
                bahan_bakar='$bahan',
                kursi='$kursi',
                harga_sewa_per_hari='$harga',
                status='$status',
                gambar_mobil='$gambar'
              WHERE id='$id'";

    if ($conn->query($query)) {
        header("Location: ../../admin/datamobil.php?updated=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Mobil</title>
    <link rel="stylesheet" href="../../admin/css/form.css">
</head>

<body>

<div class="form-container">
    <h2>Edit Mobil</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Nama Mobil</label>
        <input type="text" name="nama_mobil" value="<?= $data['nama_mobil']; ?>" required>

        <label>Tipe Mobil</label>
        <input type="text" name="tipe_mobil" value="<?= $data['tipe_mobil']; ?>" required>

        <label>Tahun</label>
        <input type="number" name="tahun" value="<?= $data['tahun']; ?>" required>

        <label>Kapasitas</label>
        <input type="number" name="kapasitas" value="<?= $data['kapasitas']; ?>" required>

        <label>Transmisi</label>
        <select name="transmisi">
            <option <?= $data['transmisi'] == "Manual" ? "selected" : "" ?>>Manual</option>
            <option <?= $data['transmisi'] == "Automatic" ? "selected" : "" ?>>Automatic</option>
            <option <?= $data['transmisi'] == "Manual/Automatic" ? "selected" : "" ?>>Manual/Automatic</option>
        </select>

        <label>Bahan Bakar</label>
        <select name="bahan_bakar">
            <option <?= $data['bahan_bakar'] == "Bensin" ? "selected" : "" ?>>Bensin</option>
            <option <?= $data['bahan_bakar'] == "Solar" ? "selected" : "" ?>>Solar</option>
        </select>

        <label>Harga Sewa / Hari</label>
        <input type="number" name="harga" value="<?= $data['harga_sewa_per_hari']; ?>" required>

        <label>Status</label>
        <select name="status">
            <option <?= $data['status'] == "Tersedia" ? "selected" : "" ?> value="Tersedia">Tersedia</option>
            <option <?= $data['status'] == "Disewa" ? "selected" : "" ?> value="Disewa">Disewa</option>
        </select>

        <label>Gambar Mobil</label>
        <input type="file" name="gambar_mobil">
        <img src="../../img/<?= $data['gambar_mobil']; ?>" width="120">

        <button type="submit" name="submit">Update Mobil</button>

    </form>
</div>

</body>
</html>
