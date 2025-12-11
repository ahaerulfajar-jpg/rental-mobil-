<?php
session_start();
include('../config/database.php');

if (!isset($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$id = $_GET['id'];

// Ambil data transaksi untuk cek sopir
$query = "SELECT pakai_sopir, sopir_id FROM transaksi WHERE id='$id'";
$result = $conn->query($query);
if ($result->num_rows == 0) {
    die("Transaksi tidak ditemukan.");
}
$data = $result->fetch_assoc();

// Update status transaksi ke "Selesai"
$conn->query("UPDATE transaksi SET status='Selesai' WHERE id='$id'");

// Jika pakai sopir, kembalikan status sopir ke "Tersedia"
if ($data['pakai_sopir'] == "ya" && !empty($data['sopir_id'])) {
    $conn->query("UPDATE sopir SET status='Tersedia' WHERE id='{$data['sopir_id']}'");
}

// Redirect dengan pesan sukses
echo "<script>
    alert('Transaksi telah ditandai sebagai selesai.');
    window.location='../../admin/transaksi.php';
</script>";
?>