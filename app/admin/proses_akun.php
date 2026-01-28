<?php
session_start();
include('../config/database.php');

$admin_id = $_POST['admin_id'];
$username = trim($_POST['username']);
$password_lama = $_POST['password_lama'];
$password_baru = $_POST['password_baru'];

/* ================= CEK PASSWORD LAMA ================= */
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Admin tidak ditemukan");
}

$data = $result->fetch_assoc();

if (!password_verify($password_lama, $data['password'])) {
    echo "<script>alert('Password lama salah');history.back();</script>";
    exit;
}

/* ================= UPDATE USERNAME ================= */
$stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
$stmt->bind_param("si", $username, $admin_id);
$stmt->execute();

/* ================= UPDATE PASSWORD (JIKA ADA) ================= */
if (!empty($password_baru)) {
    $hash = password_hash($password_baru, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hash, $admin_id);
    $stmt->execute();
}

/* ================= LOG AKTIVITAS ================= */
$stmt = $conn->prepare("
    INSERT INTO log_aktivitas (admin_id, aktivitas, jenis)
    VALUES (?, 'Mengubah username / password', 'password')
");
$stmt->bind_param("i", $admin_id);
$stmt->execute();

/* ================= REDIRECT ================= */
echo "<script>
    alert('Akun berhasil diperbarui');
    window.location='../../admin/profil.php';
</script>";
