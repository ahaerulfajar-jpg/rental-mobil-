<?php
include('../config/database.php');

$id = $_GET['id'];

// Ambil mobil_id
$data = mysqli_fetch_assoc(mysqli_query($conn,"
  SELECT mobil_id FROM perawatan_mobil WHERE id='$id'
"));

$mobil_id = $data['mobil_id'];

// Update maintenance
mysqli_query($conn,"
  UPDATE perawatan_mobil
  SET status_perawatan='selesai', tanggal_selesai=CURDATE()
  WHERE id='$id'
");

// Update mobil
mysqli_query($conn,"
  UPDATE mobil SET status='Tersedia' WHERE id='$mobil_id'
");

header("Location:  /project/admin/perawatan.php");
?>