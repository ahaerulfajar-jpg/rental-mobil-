<?php
session_start();

// Hapus semua session
$_SESSION = [];
session_destroy();

// KEMBALI KE INDEX (BUKAN LOGIN)
header("Location: index.php");
exit;
?>