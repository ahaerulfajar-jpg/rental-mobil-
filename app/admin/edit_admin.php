<?php
include('../config/database.php');
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$id";
    $conn->query($sql);
    header("Location: /project/admin/dataadmin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Admin</title>
  <link rel="stylesheet" href="../../admin/css/formadmin.css">
</head>
<body>
<div class="form-container">
  <h2>Edit Admin</h2>
  <form method="POST">
    <label>Nama Pengguna</label>
    <input type="text" name="username" value="<?= $data['username']; ?>" required>
    <label>Email</label>
    <input type="email" name="email" value="<?= $data['email']; ?>" required>
    <button type="submit" class="btn-submit">Simpan</button>
    <a href="../../admin/dataadmin.php" class="btn-cancel">Batal</a>
  </form>
</div>
</body>
</html>
