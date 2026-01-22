<?php
session_start();
include('../app/config/database.php');

// Jika sudah login
if (isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Tambahkan validasi input kosong (perbaikan kecil tanpa mengubah struktur)
    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi!";
    } else {
        $stmt = $conn->prepare("
            SELECT id, username, password, role 
            FROM users 
            WHERE email = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                   // 🔀 Redirect berdasarkan role
                    if ($user['role'] === 'pemilik') {
                        header("Location: dashboard.php");
                    } elseif ($user['role'] === 'admin') {
                        header("Location: index.php");
                    } else {
                        header("Location: index.php"); // fallback
                    }
                    exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Akun tidak ditemukan!";
        }
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
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Masukkan email anda" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
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