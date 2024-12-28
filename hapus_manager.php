<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "perpustakaan");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus data manager berdasarkan ID
if (isset($_GET['id_manager'])) {
    $id_manager = intval($_GET['id_manager']);
    $sql = "DELETE FROM manager WHERE id_manager = $id_manager";

    if ($conn->query($sql) === TRUE) {
        header("Location: lihat_manager.php?message=success");
    } else {
        header("Location: lihat_manager.php?message=error");
    }
} else {
    header("Location: lihat_manager.php?message=invalid");
}
$conn->close();
?>
