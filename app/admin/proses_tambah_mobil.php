<?php
session_start();
include('../app/config/database.php');

$response = ['status' => 'error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_mobil'];
    $tipe = $_POST['tipe_mobil'];
    $tahun = $_POST['tahun'];
    $kapasitas = $_POST['kapasitas'];
    $transmisi = $_POST['transmisi'];
    $harga = $_POST['harga_sewa_per_hari'];
    $status = $_POST['status'];

    $gambar = $_FILES['gambar_mobil']['name'];
    $target = "/project/img/" . basename($gambar);
    move_uploaded_file($_FILES['gambar_mobil']['tmp_name'], $target);

    $sql = "INSERT INTO mobil (nama_mobil, tipe_mobil, tahun, kapasitas, transmisi, harga_sewa_per_hari, status, gambar_mobil)
            VALUES ('$nama', '$tipe', '$tahun', '$kapasitas', '$transmisi', '$harga', '$status', '$gambar')";
    
    if ($conn->query($sql)) {
        $response['status'] = 'success';
    }
}

echo json_encode($response);
?>