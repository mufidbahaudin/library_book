<?php
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $role = $_POST['role']; // Tambahkan role

    if ($password === $confirmPassword) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");

        try {
            $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'role' => $role]);
            header("Location: login.php");
        } catch (PDOException $e) {
            $error = "Username sudah terdaftar.";
        }
    } else {
        $error = "Password tidak sesuai.";
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
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="wrapper d-flex align-items-center justify-content-center h-100">
            <div class="card login-form">
                <div class="card-body">
                    <h3 class="card-title text-center">Register</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="username" placeholder="Username Baru" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password Baru" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password" required>
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
                        <button type="submit" class="btn btn-primary w-100">Daftar</button>
                        <div class="sign-up mt-4">
                            Sudah ada akun? <a href="login.php">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>