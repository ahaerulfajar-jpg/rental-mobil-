<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('app/config/database.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email == "" || $password == "") {
        $error = "Email dan password wajib diisi.";
    } else {

        $stmt = $conn->prepare("SELECT id, nama, email, password FROM pelanggan WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {

            $stmt->bind_result($id, $nama, $emailDB, $hashed);
            $stmt->fetch();

            if (password_verify($password, $hashed)) {

                $_SESSION['user_id']  = $id;
                $_SESSION['nama']     = $nama;
                $_SESSION['email']    = $emailDB;

                header("Location: index.php");
                exit;
            } else {
                $error = "Password salah.";
            }

        } else {
            $error = "Email tidak ditemukan.";
        }

        $stmt->close();
    }
}

if (isset($_GET['success'])) {
    echo "<script>alert('Pendaftaran berhasil! Silakan login.');</script>";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simpati Trans Makassar</title>
  <link rel="stylesheet" href="css/auth.css?v=2.1">
  <link href="https://cdn.jsdelivr.net/npm/bootstlare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="auth-container">
  <div class="auth-box">
    <img src="img/logo1.png" class="logo" alt="Logo Simpati Trans">
    <h2>Login</h2>
    <p class="subtitle">Masukkan email dan password untuk melanjutkan</p>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required placeholder="Masukkan email anda">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required placeholder="Masukkan password">
      </div>

      <button type="submit" class="btn-auth">Login</button>
    </form>

    <p class="switch-link">Belum punya akun? <a href="register1.php">Daftar Sekarang</a></p>
  </div>
</div>

</body>
</html>
