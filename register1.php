<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('app/config/database.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if ($nama == "" || $email == "" || $password == "") {
        $error = "Semua data wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {

        $cek = $conn->prepare("SELECT id FROM pelanggan WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error = "Email sudah digunakan.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $add = $conn->prepare("INSERT INTO pelanggan (nama, email, password) VALUES (?, ?, ?)");
            $add->bind_param("sss", $nama, $email, $hash);

            if ($add->execute()) {
                header("Location: login1.php?success=1");
                exit;
            } else {
                $error = "Gagal menyimpan data.";
            }

            $add->close();
        }

        $cek->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Pelanggan - Simpati Trans</title>
  <link rel="stylesheet" href="css/auth.css?v=2.1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="auth-container">
  <div class="auth-box">
    <img src="../project/img/logo1.png" class="logo" alt="Logo Simpati Trans">

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" required placeholder="Masukkan nama lengkap">
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required placeholder="Masukkan email aktif">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required placeholder="Masukkan password">
      </div>

      <button type="submit" class="btn-auth">Daftar</button>
    </form>

    <p class="switch-link">Sudah punya akun? <a href="login1.php">Login di sini</a></p>
  </div>
</div>

</body>
</html>