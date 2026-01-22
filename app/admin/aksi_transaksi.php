<?php
session_start();
include('../config/database.php');

// ===============================
// VALIDASI PARAMETER
// ===============================
if (!isset($_GET['id'], $_GET['action'])) {
    die("Parameter tidak valid.");
}

$id = (int) $_GET['id'];
$action = $_GET['action'];

// ===============================
// AMBIL DATA TRANSAKSI
// ===============================
$stmt = $conn->prepare("
    SELECT status, pakai_sopir, sopir_id 
    FROM transaksi 
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Transaksi tidak ditemukan.");
}

$data = $result->fetch_assoc();

// ===============================
// TENTUKAN STATUS BARU
// ===============================
switch ($action) {
    case 'setuju':
        if ($data['status'] !== 'Menunggu') {
            die("Transaksi tidak bisa disetujui.");
        }
        $statusBaru = 'Disetujui';
        break;

    case 'tolak':
        if ($data['status'] !== 'Menunggu') {
            die("Transaksi tidak bisa ditolak.");
        }
        $statusBaru = 'Ditolak';
        break;

    case 'selesai':
        if ($data['status'] !== 'Disetujui') {
            die("Hanya transaksi Disetujui yang bisa diselesaikan.");
        }
        $statusBaru = 'Selesai';
        break;

    default:
        die("Aksi tidak dikenali.");
}

// ===============================
// UPDATE STATUS TRANSAKSI
// ===============================
$stmt = $conn->prepare("
    UPDATE transaksi 
    SET status = ? 
    WHERE id = ?
");
$stmt->bind_param("si", $statusBaru, $id);
$stmt->execute();

// ===============================
// JIKA SELESAI & PAKAI SOPIR
// ===============================
if (
    $action === 'selesai' &&
    $data['pakai_sopir'] === 'ya' &&
    !empty($data['sopir_id'])
) {
    $stmt = $conn->prepare("
        UPDATE sopir 
        SET status = 'Tersedia' 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $data['sopir_id']);
    $stmt->execute();
}

// ===============================
// REDIRECT
// ===============================
echo "<script>
    alert('Status transaksi berhasil diperbarui!');
    window.location='../../admin/transaksi.php';
</script>";
?>