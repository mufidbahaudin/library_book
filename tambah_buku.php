<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
session_regenerate_id(true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "perpustakaan");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari form dan sanitasi
    $id_buku = $conn->real_escape_string(trim($_POST['id_buku']));
    $judul_buku = $conn->real_escape_string(trim($_POST['judul_buku']));
    $penulis_buku = $conn->real_escape_string(trim($_POST['penulis_buku']));
    $tahun_terbit = $conn->real_escape_string(trim($_POST['tahun_terbit']));
    $genre = $conn->real_escape_string(trim($_POST['genre']));

    // Handle upload gambar
    $target_dir = "uploads/"; // Path relatif untuk folder uploads
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Buat folder jika belum ada
    }

    if (isset($_FILES['gambar_buku']) && $_FILES['gambar_buku']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['gambar_buku']['name']);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi tipe file
        $valid_extensions = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $valid_extensions)) {
            $error_message = "Hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.";
        } elseif (move_uploaded_file($_FILES['gambar_buku']['tmp_name'], $target_file)) {
            // Simpan data buku ke database
            $stmt = $conn->prepare("INSERT INTO buku (id_buku, gambar_buku, judul_buku, penulis_buku, tahun_terbit, genre) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $id_buku, $file_name, $judul_buku, $penulis_buku, $tahun_terbit, $genre);

            if ($stmt->execute()) {
                $success_message = "Buku berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menambahkan buku: " . $conn->error;
            }

            $stmt->close();
        } else {
            $error_message = "Gagal mengunggah file gambar.";
        }
    } else {
        $upload_error = $_FILES['gambar_buku']['error'];
        switch ($upload_error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error_message = "File terlalu besar.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $error_message = "File gambar wajib diunggah.";
                break;
            default:
                $error_message = "Terjadi kesalahan saat mengunggah file.";
        }
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Tambah Buku</title>
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
                    <li class="nav-item active dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pilihan Buku
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="tambah_buku.php">Tambah Buku</a></li>
                            <li><a class="dropdown-item" href="lihat_buku.php">Lihat Buku</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manager Perpustakaan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="tambah_manager.php">Tambah Manager</a></li>
                            <li><a class="dropdown-item" href="lihat_manager.php">Lihat Manager</a></li>
                        </ul>
                    </li>
                </ul>
                <a href="logout.php" class="btn btn-danger px-4">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="alert alert-success" role="alert">
            Hello, <?= htmlspecialchars($_SESSION['username']) ?>!. Anda adalah <strong>ADMIN</strong>, anda bisa melakukan tambah buku
        </div>
        <h2 class="text-center">Tambah Buku</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <form action="tambah_buku.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="id_buku" class="form-label">ID/Kode Buku</label><strong class="text-danger">*</strong>
                <input type="text" class="form-control" id="id_buku" name="id_buku" placeholder="Masukan ID/Kode Buku" required>
            </div>
            <div class="mb-3">
                <label for="gambar_buku" class="form-label">Gambar Buku</label><strong class="text-danger">*</strong>
                <input type="file" class="form-control" id="gambar_buku" name="gambar_buku" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="judul_buku" class="form-label">Judul Buku</label><strong class="text-danger">*</strong>
                <input type="text" class="form-control" id="judul_buku" name="judul_buku" placeholder="Masukan Judul Buku" required>
            </div>
            <div class="mb-3">
                <label for="penulis_buku" class="form-label">Penulis Buku</label><strong class="text-danger">*</strong>
                <input type="text" class="form-control" id="penulis_buku" name="penulis_buku" placeholder="Masukan Siapa Penulis Buku?" required>
            </div>
            <div class="mb-3">
                <label for="tahun_terbit" class="form-label">Tahun Terbit</label><strong class="text-danger">*</strong>
                <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" placeholder="Masukan Kapan Tahun Terbit Buku?" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label><strong class="text-danger">*</strong>
                <input type="text" class="form-control" id="genre" name="genre" placeholder="Masukan Genre Buku" required>
            </div>
            <p>Note : <strong class="text-danger">*</strong> (Wajib Diisi)</p>
            <button type="submit" class="btn btn-primary">Tambah Buku</button>
        </form>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>