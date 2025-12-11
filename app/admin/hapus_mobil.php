<?php
include('../config/database.php'); // path yang benar

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil gambar mobil
    $query_gambar = $conn->query("SELECT gambar_mobil FROM mobil WHERE id = $id");
    $data = $query_gambar->fetch_assoc();
    $file = "../../img/" . $data['gambar_mobil'];

    // Hapus file gambar
    if (file_exists($file)) {
        unlink($file);
    }

    // Hapus data dari database
    $conn->query("DELETE FROM mobil WHERE id = $id");

    // Redirect kembali ke halaman data mobil
    header("Location: ../../admin/datamobil.php?status=deleted");
    exit();
} else {
    header("Location: ../../admin/datamobil.php?status=error");
    exit();
}
?>