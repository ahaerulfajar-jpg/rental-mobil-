<?php
include('../config/database.php');

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM mobil WHERE id='$id'")->fetch_assoc();

echo json_encode($data);
?>