<?php
include('../config/database.php');

$id = $_GET['id'];

// ambil mobil_id
$data = mysqli_fetch_assoc(mysqli_query($conn,
  "SELECT mobil_id FROM perawatan_mobil WHERE id='$id'"
));

$mobil_id = $data['mobil_id'];

// update status maintenance
mysqli_query($conn,"
  UPDATE perawatan_mobil 
  SET status_perawatan='selesai', tanggal_selesai=CURDATE()
  WHERE id='$id'
");

// kembalikan status mobil
mysqli_query($conn,"
  UPDATE mobil SET status='Tersedia'
  WHERE id='$mobil_id'
");

header("Location: ../../admin/perawatan.php");
