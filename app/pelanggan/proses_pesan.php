<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login1.php");
    exit;
}

$pelanggan_id = $_SESSION['user_id'];

// Ambil data pelanggan
$pelanggan = $conn->query("SELECT * FROM pelanggan WHERE id='$pelanggan_id'")->fetch_assoc();

if (!$pelanggan) {
    die("Error: Data pelanggan tidak ditemukan.");
}

$nama    = $pelanggan['nama'];
$email   = $pelanggan['email'];
$telepon = $_POST['telepon'];  
$mobil_id    = $_POST['mobil_id'];
$pakai_sopir = $_POST['pakai_sopir'];
$sopir_id    = ($pakai_sopir == "ya") ? ($_POST['sopir_id'] ?? NULL) : NULL;
$mulai       = $_POST['tanggal_mulai'];
$selesai     = $_POST['tanggal_selesai'];
$jam_mulai   = $_POST['jam_mulai'];
$alamat      = $_POST['alamat_jemput'];
$catatan     = $_POST['catatan'];

// Ambil harga mobil
$mobil = $conn->query("SELECT harga_sewa_per_hari FROM mobil WHERE id='$mobil_id'")->fetch_assoc();
$harga_per_hari = $mobil['harga_sewa_per_hari'];

// Hitung durasi
$durasi = ceil( (strtotime($selesai) - strtotime($mulai)) / 86400 );
$durasi = max(1, $durasi);

// Hitung biaya sopir jika dipilih
$biaya_sopir_total = 0;
if ($pakai_sopir == "ya" && $sopir_id) {
    $sopir_data = $conn->query("SELECT harga_per_hari FROM sopir WHERE id='$sopir_id'")->fetch_assoc();
    $biaya_sopir_per_hari = $sopir_data['harga_per_hari'];
    $biaya_sopir_total = $durasi * $biaya_sopir_per_hari;
}

// Hitung total harga (mobil + sopir)
$total  = $durasi * $harga_per_hari + $biaya_sopir_total;

// Simpan transaksi
$sql = "INSERT INTO transaksi (
    mobil_id, pelanggan_id, nama_pelanggan, email, telepon,
    tanggal_mulai, jam_mulai, tanggal_selesai, alamat_jemput, catatan,
    total_harga, pakai_sopir, sopir_id, biaya_sopir, status
)
VALUES (
    '$mobil_id','$pelanggan_id','$nama','$email','$telepon',
    '$mulai', '$jam_mulai', '$selesai', '$alamat', '$catatan',
    '$total', '$pakai_sopir', " . ($sopir_id ? "'$sopir_id'" : "NULL") . ",
    '$biaya_sopir_total','Menunggu'
)";

// Jika pakai sopir → ubah status sopir
if ($pakai_sopir == "ya" && $sopir_id) {
    $conn->query("UPDATE sopir SET status='dipesan' WHERE id='$sopir_id'");
}

// Eksekusi transaksi
if ($conn->query($sql)) {
    echo 
    "<script>
        alert('Pesanan berhasil dikirim! Menunggu konfirmasi admin.');
        window.location='../../pesanan.php';
    </script>";
} else {
    echo "Error: " . $conn->error;
}