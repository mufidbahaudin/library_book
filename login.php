<?php
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Ambil role dari form

    // Query untuk mencocokkan username dan role
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND role = :role");
    $stmt->execute(['username' => $username, 'role' => $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role']; // Simpan role ke sesi

        // Arahkan berdasarkan role
        if ($user['role'] === 'admin') {
            header("Location: dashboard.php");
        } elseif ($user['role'] === 'reader') {
            header("Location: reader_dashboard.php");
        }
    } else {
        $error = "Username, password, atau role salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="wrapper d-flex align-items-center justify-content-center h-100">
        <div class="card login-form">
            <div class="card-body">
                <h3 class="card-title text-center">Login</h3>
                <form method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Pilihan</label>
                        <select class="form-control" name="role" id="role" required>
                            <option value="reader">Reader</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                    <div class="sign-up mt-4">
                        Belum ada akun? <a href="register.php">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
