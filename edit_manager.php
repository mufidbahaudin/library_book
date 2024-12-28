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

// Ambil data manager berdasarkan ID
if (isset($_GET['id_manager'])) {
    $id_manager = intval($_GET['id_manager']);
    $result = $conn->query("SELECT * FROM manager WHERE id_manager = $id_manager");

    if ($result->num_rows > 0) {
        $manager = $result->fetch_assoc();
    } else {
        header("Location: lihat_manager.php?message=invalid");
        exit();
    }
}

// Proses update data manager
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_manager = $conn->real_escape_string($_POST['nama_manager']);
    $jabatan = $conn->real_escape_string($_POST['jabatan']);

    $sql = "UPDATE manager SET nama_manager = '$nama_manager', jabatan = '$jabatan' WHERE id_manager = $id_manager";

    if ($conn->query($sql) === TRUE) {
        header("Location: lihat_manager.php?message=success");
    } else {
        echo "Error: " . $conn->error;
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
    <title>Edit Manager</title>
    <style>
        nav {
            background-color: #24242c;
        }
    </style>
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
                            <li><a class="dropdown-item" href="tambah_manager.php">Tambah Manager</a></li>
                            <li><a class="dropdown-item active" href="lihat_manager.php">Lihat Manager</a></li>
                        </ul>
                    </li>
                </ul>
                <a href="logout.php" class="btn btn-danger px-4">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Edit Manager</h2>
        <form method="post">
            <div class="mb-3">
                <label for="nama_manager" class="form-label">Nama Manager</label>
                <input type="text" class="form-control" id="nama_manager" name="nama_manager" value="<?= htmlspecialchars($manager['nama_manager']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= htmlspecialchars($manager['jabatan']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="lihat_manager.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

</html>
<?php $conn->close(); ?>