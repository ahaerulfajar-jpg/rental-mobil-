<?php
session_start();
include(__DIR__ . '/../config/database.php');
include(__DIR__ . '/../config/app.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../admin/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../admin/galeri_mobil.php');
    exit; 
}

$mobil_id = isset($_POST['mobil_id']) ? (int) $_POST['mobil_id'] : 0;
$kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
$keterangan = isset($_POST['keterangan']) ? trim($_POST['keterangan']) : null;
$allowed_kategori = ['eksterior', 'interior', 'mesin', 'lainnya'];
$allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$max_size = 5 * 1024 * 1024; // 5MB

if ($mobil_id <= 0 || !in_array($kategori, $allowed_kategori)) {
    header('Location: ../../admin/galeri_mobil.php?mobil_id=' . $mobil_id);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM mobil WHERE id = ?");
$stmt->bind_param('i', $mobil_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    $stmt->close();
    header('Location: ../../admin/galeri_mobil.php');
    exit;
}
$stmt->close();

if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../../admin/galeri_mobil.php?mobil_id=' . $mobil_id);
    exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($_FILES['gambar']['tmp_name']);
$allowed_mime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($mime, $allowed_mime) || $_FILES['gambar']['size'] > $max_size) {
    header('Location: ../../admin/galeri_mobil.php?mobil_id=' . $mobil_id);
    exit;
}

$ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed_ext)) {
    header('Location: ../../admin/galeri_mobil.php?mobil_id=' . $mobil_id);
    exit;
}

$root = dirname(__DIR__, 2);
$dir = $root . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'mobil' . DIRECTORY_SEPARATOR . $mobil_id;
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$nama_file = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['gambar']['name']));
if (!pathinfo($nama_file, PATHINFO_EXTENSION)) {
    $nama_file .= '.' . $ext;
}
$path_file = $dir . DIRECTORY_SEPARATOR . $nama_file;

if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $path_file)) {
    header('Location: ../../admin/galeri_mobil.php?mobil_id=' . $mobil_id);
    exit;
}

$base_url = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$url_gambar = $base_url . '/img/mobil/' . $mobil_id . '/' . $nama_file;

$stmt = $conn->prepare("INSERT INTO mobil_gambar (mobil_id, kategori, nama_file, keterangan, url_gambar) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('issss', $mobil_id, $kategori, $nama_file, $keterangan, $url_gambar);
$stmt->execute();
$stmt->close();

header('Location: ../../admin/galeri_mobil.php?mobil_id=' . $mobil_id);
exit;
