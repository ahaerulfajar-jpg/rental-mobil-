<?php
include "../config/database.php";

$nama = $_POST['nama'];
$telepon = $_POST['telepon'];
$harga = $_POST['harga'];

$fotoName = time() . "_" . $_FILES['foto']['name'];
$target = "../../img/" . $fotoName;
move_uploaded_file($_FILES['foto']['tmp_name'], $target);

$conn->query("INSERT INTO sopir (nama, telepon, harga_per_hari, foto)
              VALUES ('$nama', '$telepon', '$harga', '$fotoName')");

header("Location: ../../../../admin/sopir.php");