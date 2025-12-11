<?php
session_start();
include('../app/config/database.php');

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email' AND role = 'admin'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['admin'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan atau bukan admin!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Simpati Trans</title>
    <link rel="stylesheet" href="../admin/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <img src="../img/logo1.png" class="logo" alt="Logo Simpati Trans">

        <h2>Login Admin</h2>
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

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="footer-text">
            <p>©2024 CV. Simpati Trans Makassar</p>
        </div>
    </div>
</div>

</body>
</html>
