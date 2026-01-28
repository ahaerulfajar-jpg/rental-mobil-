<?php
session_start();
include('../config/database.php');

// Ambil ID dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: /admin/sopir.php");
    exit;
}

// Hapus sopir
$stmt = $conn->prepare("DELETE FROM sopir WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: /admin/sopir.php?msg=deleted");
    exit;
} else {
    echo "Gagal hapus sopir.";
}
$stmt->close();
?>