<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

// Pastikan id_buku diterima melalui URL
if (isset($_GET['id_buku'])) {
  $id_buku = $_GET['id_buku'];

  // Koneksi ke database
  $conn = new mysqli("localhost", "root", "", "perpustakaan");

  if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
  }

  // Ambil data gambar buku sebelum menghapus
  $query = $conn->prepare("SELECT gambar_buku FROM buku WHERE id_buku = ?");
  $query->bind_param("s", $id_buku);
  $query->execute();
  $query->bind_result($gambar_buku);
  $query->fetch();
  $query->close();

  // Hapus file gambar jika ada
  if ($gambar_buku && file_exists($gambar_buku)) {
    unlink($gambar_buku);
  }

  // Hapus buku dari database
  $stmt = $conn->prepare("DELETE FROM buku WHERE id_buku = ?");
  $stmt->bind_param("s", $id_buku);

  if ($stmt->execute()) {
    // Redirect kembali ke lihat_buku.php dengan pesan sukses
    header("Location: lihat_buku.php?message=success");
    exit();
  } else {
    // Redirect dengan pesan error
    header("Location: lihat_buku.php?message=error");
    exit();
  }

  $stmt->close();
  $conn->close();
} else {
  // Redirect jika id_buku tidak disediakan
  header("Location: lihat_buku.php?message=invalid");
  exit();
}
?>
