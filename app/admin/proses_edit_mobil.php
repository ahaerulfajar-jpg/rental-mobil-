<?php
include('../app/config/database.php');
$response = ['status' => 'error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama_mobil'];
    $tipe = $_POST['tipe_mobil'];
    $tahun = $_POST['tahun'];
    $kapasitas = $_POST['kapasitas'];
    $transmisi = $_POST['transmisi'];
    $harga = $_POST['harga_sewa_per_hari'];
    $status = $_POST['status'];

    if (!empty($_FILES['gambar_mobil']['name'])) {
        $gambar = $_FILES['gambar_mobil']['name'];
        $target = "../img/" . basename($gambar);
        move_uploaded_file($_FILES['gambar_mobil']['tmp_name'], $target);
        $gambarQuery = ", gambar_mobil='$gambar'";
    } else {
        $gambarQuery = "";
    }

    $sql = "UPDATE mobil SET 
            nama_mobil='$nama', tipe_mobil='$tipe', tahun='$tahun', kapasitas='$kapasitas', 
            transmisi='$transmisi', harga_sewa_per_hari='$harga', status='$status' $gambarQuery 
            WHERE id='$id'";
    
    if ($conn->query($sql)) {
        $response['status'] = 'success';
    }
}

echo json_encode($response);
?>
