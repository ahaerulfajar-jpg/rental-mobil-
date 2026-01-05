<?php
include('../config/database.php');

// Validasi parameter GET
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    echo "<script>alert('Parameter tidak valid.'); window.location='../../admin/transaksi.php';</script>";
    exit();
}

$id = $_GET['id'];
$action = $_GET['action'];

// Set status berdasarkan action yang valid
$valid_actions = ['setuju' => 'Disetujui', 'tolak' => 'Ditolak', 'selesai' => 'Selesai'];
if (!array_key_exists($action, $valid_actions)) {
    echo "<script>alert('Aksi tidak valid.'); window.location='../../admin/transaksi.php';</script>";
    exit();
}
$status = $valid_actions[$action];

// Gunakan prepared statement untuk mencegah SQL injection
$stmt = $conn->prepare("UPDATE transaksi SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id); // "s" untuk string, "i" untuk integer
if ($stmt->execute()) {
    echo "<script>alert('Status berhasil diperbarui.'); window.location='../../admin/transaksi.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui status: " . $stmt->error . "'); window.location='../../admin/transaksi.php';</script>";
}
$stmt->close();
$conn->close();
?>