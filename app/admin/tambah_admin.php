<?php
include('../config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'admin')";
    if ($conn->query($sql)) {
        header("Location: /project/admin/dataadmin.php");
        exit;
    } else {
        $error = "Gagal menambah admin: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Admin | Simpati Trans</title>
  <link rel="stylesheet" href="../../admin/css/formadmin.css">
</head>
<body>
<div class="form-container">
  <h2>Tambah Admin</h2>
  <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
  <form method="POST">
    <label>Nama Pengguna</label>
    <input type="text" name="username" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit" class="btn-submit">Simpan</button>
    <a href="../../admin/dataadmin.php" class="btn-cancel">Batal</a>
  </form>
</div>
</body>
</html>
