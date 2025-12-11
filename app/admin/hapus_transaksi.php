<?php
include('../config/database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM transaksi WHERE id='$id'";
    if ($conn->query($query)) {
        echo "<script>alert('Transaksi berhasil dihapus!'); window.location='../../admin/transaksi.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus transaksi!'); window.location='../../admin/transaksi.php';</script>";
    }
}
?>
