<?php
include('../config/database.php');

$id = $_GET['id'];

// Ambil data admin
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$admin = $result->fetch_assoc();

// Proses update password baru
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['password'] === $_POST['password2']) {
        $password_baru = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $conn->query("UPDATE users SET password = '$password_baru' WHERE id = $id");
        header("Location: /project/admin/dataadmin.php");
        exit;
    } else {
        $error = "Password tidak sama!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password Admin</title>
  <link rel="stylesheet" href="../../admin/css/formadmin.css">
</head>
<body>
  <main class="main-content">
    <div class="form-container">
      <h2>Reset Password untuk <?= htmlspecialchars($admin['username']); ?></h2>

      <?php if (!empty($error)): ?>
        <p class="error"><?= $error; ?></p>
      <?php endif; ?>

      <form method="POST">
        <label>Password Baru</label>
        <input type="password" name="password" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="password2" required>

        <button type="submit" class="btn-save">Simpan Password Baru</button>
        <a href="../../admin/dataadmin.php" class="btn-cancel">Batal</a>
      </form>
    </div>
  </main>
</body>
</html>
