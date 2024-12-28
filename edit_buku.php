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

// Ambil data buku berdasarkan ID
if (isset($_GET['id_buku'])) {
    $id_buku = $_GET['id_buku'];
    $stmt = $conn->prepare("SELECT * FROM buku WHERE id_buku = ?");
    $stmt->bind_param("s", $id_buku);
    $stmt->execute();
    $result = $stmt->get_result();
    $buku = $result->fetch_assoc();
    $stmt->close();

    if (!$buku) {
        die("Buku dengan ID tersebut tidak ditemukan.");
    }
} else {
    die("ID Buku tidak disediakan.");
}

// Proses update data buku
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_buku = $_POST['judul_buku'];
    $penulis_buku = $_POST['penulis_buku'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $genre = $_POST['genre'];
    $gambar_buku = $buku['gambar_buku'];

    // Cek apakah ada file baru yang diunggah
    if (isset($_FILES['gambar_buku']) && $_FILES['gambar_buku']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['gambar_buku']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $valid_extensions)) {
            if (move_uploaded_file($_FILES['gambar_buku']['tmp_name'], $target_file)) {
                $gambar_buku = $target_file; // Perbarui dengan file baru
            } else {
                $error_message = "Gagal mengunggah file gambar.";
            }
        } else {
            $error_message = "Hanya file JPG, JPEG, PNG, dan GIF yang diizinkan.";
        }
    }

    // Update data buku
    if (!isset($error_message)) {
        $stmt = $conn->prepare("UPDATE buku SET judul_buku = ?, penulis_buku = ?, tahun_terbit = ?, genre = ?, gambar_buku = ? WHERE id_buku = ?");
        $stmt->bind_param("ssssss", $judul_buku, $penulis_buku, $tahun_terbit, $genre, $gambar_buku, $id_buku);

        if ($stmt->execute()) {
            $success_message = "Buku berhasil diperbarui!";
            $buku['judul_buku'] = $judul_buku;
            $buku['penulis_buku'] = $penulis_buku;
            $buku['tahun_terbit'] = $tahun_terbit;
            $buku['genre'] = $genre;
            $buku['gambar_buku'] = $gambar_buku;
        } else {
            $error_message = "Gagal memperbarui buku: " . $conn->error;
        }

        $stmt->close();
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
    <title>Edit Buku</title>
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
              <li><a class="dropdown-item" href="tambah_buku.php">Tambah Buku</a></li>
              <li><a class="dropdown-item active" href="lihat_buku.php">Lihat Buku</a></li>
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
        <h2>Edit Buku</h2>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <form action="edit_buku.php?id_buku=<?= htmlspecialchars($id_buku) ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="judul_buku" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" id="judul_buku" name="judul_buku" value="<?= htmlspecialchars($buku['judul_buku']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="penulis_buku" class="form-label">Penulis Buku</label>
                <input type="text" class="form-control" id="penulis_buku" name="penulis_buku" value="<?= htmlspecialchars($buku['penulis_buku']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="<?= htmlspecialchars($buku['tahun_terbit']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre" value="<?= htmlspecialchars($buku['genre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="gambar_buku" class="form-label">Gambar Buku (Opsional)</label>
                <input type="file" class="form-control" id="gambar_buku" name="gambar_buku" accept="image/*">
                <div class="mt-2">
                    <img src="<?= htmlspecialchars($buku['gambar_buku']) ?>" alt="Gambar Buku" style="width: 100px; height: auto;">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="lihat_buku.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>