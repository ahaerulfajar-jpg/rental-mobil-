<?php
session_start();
include('../config/database.php');

// Cek akses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pemilik') {
    header("Location: ../../admin/index.php");
    exit;
}

$username = trim($_POST['username']);
$email    = trim($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role     = 'admin';

// Simpan ke DB
$stmt = $conn->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("ssss", $username, $email, $password, $role);

if ($stmt->execute()) {
    header("Location: ../../admin/dataadmin.php?success=1");
} else {
    echo "Gagal menambahkan admin";
}
