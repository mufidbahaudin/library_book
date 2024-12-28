<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
session_regenerate_id(true);

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "perpustakaan");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses form
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_manager = $conn->real_escape_string($_POST['nama_manager']);
    $jabatan = $conn->real_escape_string($_POST['jabatan']);

    // Simpan data manager
    $query = "INSERT INTO manager (nama_manager, jabatan) VALUES ('$nama_manager', '$jabatan')";
    if ($conn->query($query)) {
        $message = '<div class="alert alert-success">Manager berhasil ditambahkan!</div>';
    } else {
        $message = '<div class="alert alert-danger">Terjadi kesalahan saat menambahkan manager!</div>';
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Tambah Manager</title>
    <link rel="stylesheet" href="css/admin_ngedit.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
        <div class="container-fluid">
            <h3 class="text-white">RuangBook</h3>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pilihan Buku
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="tambah_buku.php">Tambah Buku</a></li>
                            <li><a class="dropdown-item" href="lihat_buku.php">Lihat Buku</a></li>
                        </ul>
                    </li>
                    <li class="nav-item active dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manager Perpustakaan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="tambah_manager.php">Tambah Manager</a></li>
                            <li><a class="dropdown-item" href="lihat_manager.php">Lihat Manager</a></li>
                        </ul>
                    </li>
                </ul>
                <a href="logout.php" class="btn btn-danger px-4">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Tambah Manager</h2>
        <div class="alert alert-success" role="alert">
            Hello, <?= htmlspecialchars($_SESSION['username']) ?>!. Anda adalah <strong>ADMIN</strong>, anda bisa melakukan tambah manager
        </div>
        <?= $message ?>
        <form action="tambah_manager.php" method="post">
            <div class="mb-3">
                <label for="nama_manager" class="form-label">Nama Manager <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control" id="nama_manager" name="nama_manager" placeholder="Masukan Nama Manager" required>
            </div>
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukan Jabatan Manager" required>
            </div>
            <p>Note : <strong class="text-danger">*</strong> (Wajib Diisi)</p>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>