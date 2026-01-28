<?php
include('../config/database.php');

$id = $_GET['id'];

$query = "
SELECT 
  pm.*,
  m.nama_mobil
FROM perawatan_mobil pm
JOIN mobil m ON pm.mobil_id = m.id
WHERE pm.id = '$id'
";

$data = mysqli_fetch_assoc(mysqli_query($conn, $query));

echo json_encode($data);
?>