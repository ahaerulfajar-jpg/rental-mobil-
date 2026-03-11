<?php
session_start();
include(__DIR__ . '/../config/database.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../admin/index.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int) $_POST['id'] : 0);
$mobil_id_param = isset($_GET['mobil_id']) ? (int) $_GET['mobil_id'] : null;

if ($id <= 0) {
    header('Location: ../../admin/galeri_mobil.php' . ($mobil_id_param ? '?mobil_id=' . $mobil_id_param : ''));
    exit;
}

$stmt = $conn->prepare("SELECT id, mobil_id, nama_file FROM mobil_gambar WHERE id = ?");
$stmt->bind_param('i', $id); 
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $stmt->close();
    header('Location: ../../admin/galeri_mobil.php' . ($mobil_id_param ? '?mobil_id=' . $mobil_id_param : ''));
    exit;
}
$row = $res->fetch_assoc();
$mobil_id = (int) $row['mobil_id'];
$nama_file = $row['nama_file'];
$stmt->close();

$root = dirname(__DIR__, 2);
$path_file = $root . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'mobil' . DIRECTORY_SEPARATOR . $mobil_id . DIRECTORY_SEPARATOR . $nama_file;
if (is_file($path_file)) {
    unlink($path_file);
}

$stmt = $conn->prepare("DELETE FROM mobil_gambar WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

header('Location: ../../admin/galeri_mobil.php?mobil_id=' . $mobil_id);
exit;
