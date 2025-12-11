<?php
include('../config/database.php');

$id = $_GET['id'];
$action = $_GET['action'];

if ($action == "setuju") {
    $status = "Disetujui";
} elseif ($action == "tolak") {
    $status = "Ditolak";
} elseif ($action == "selesai") {
    $status = "Selesai";
}

$conn->query("UPDATE transaksi SET status='$status' WHERE id='$id'");

echo "<script>alert('Status berhasil diperbarui.');window.location='../../admin/transaksi.php';</script>";
?>
