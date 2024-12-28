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

// Hitung total buku
$result_buku = $conn->query("SELECT COUNT(*) AS total_buku FROM buku");
$total_buku = $result_buku->fetch_assoc()['total_buku'] ?? 0;

// Hitung total manager
$result_manager = $conn->query("SELECT COUNT(*) AS total_manager FROM manager");
$total_manager = $result_manager->fetch_assoc()['total_manager'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/dboard.css">
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
            <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
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

  <section class="jumbotron text-center text-white">
    <div class="px-4 py-5 my-5">
      <h5>Hello, <?= htmlspecialchars($_SESSION['username']) ?>!.</h5>
      <h1>Selamat Datang di RuangBook</h1>
      <h5>A place where knowledge meets inspiration</h5>
      <a href="tambah_buku.php" class="btn btn-primary mt-3">Ingin Tambah Buku?</a>
    </div>
  </section>

  <section id="about">
    <div class="container">
      <!-- Section Title -->
      <div class="row text-center mb-4">
        <div class="col">
          <h1>About Us</h1>
        </div>
      </div>
      <!-- Content -->
      <div class="row">
        <div class="col text-center">
          <p class="fs-5">
            Perpustakaan RuangBook didirikan pada 21 November 2024 dengan semangat menciptakan lingkungan belajar yang inklusif dan mendukung pengembangan literasi bagi masyarakat.
            Berlokasi di tempat yang strategis, RuangBook hadir sebagai solusi modern bagi para pecinta buku, pelajar, dan peneliti untuk mendapatkan akses mudah ke berbagai koleksi buku dan sumber informasi berkualitas.
            Tujuan utama dari Perpustakaan RuangBook adalah mempermudah literasi masyarakat, mempermudah akses buku, dan menciptakan ruang edukasi.
            Dengan perpaduan teknologi dan kenyamanan, RuangBook tidak hanya sekadar tempat untuk membaca, tetapi juga menjadi pusat komunitas belajar yang memberdayakan generasi masa depan.
            Mari bergabung bersama kami di Perpustakaan RuangBook untuk menjelajahi dunia ilmu pengetahuan tanpa batas.
          </p>
        </div>
      </div>
    </div>
  </section>


  <section id="function">
    <div class="container">
      <div class="row text-center mb-3">
        <div class="col">
          <h1>Benefits</h1>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
          <div class="card">
            <img src="img/becca-tapert-GnY_mW1Q6Xc-unsplash.jpg" class="card-img-top" alt="project1">
            <div class="card-body">
              <h4 class="fs-5">Meningkatkan Pengetahuan Membaca</h4>
              <button class="btn btn-primary text-white">Read More..</button>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card">
            <img src="img/eliott-reyna-kcT-7cirBEw-unsplash.jpg" class="card-img-top" alt="project4">
            <div class="card-body">
              <h4 class="fs-5">Meningkatkan Keterampilan Bahasa</h4>
              <button class="btn btn-primary text-white">Read More..</button>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card">
            <img src="img/nick-fewings-f2Bi-VBs71M-unsplash.jpg" class="card-img-top" alt="project5">
            <div class="card-body">
              <h4 class="fs-5">Mengisi Waktu dengan Kegiatan Positif</h4>
              <button class="btn btn-primary text-white">Read More..</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="available">
    <div class="container text-center">
      <!-- Section Title -->
      <div class="row text-center mb-4">
        <div class="col">
          <h1>Available</h1>
        </div>
      </div>
      <!-- Cards -->
      <div class="row">
        <!-- Card 1: Total Buku -->
        <div class="col-sm-6 mb-3">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <img src="img/book.png" style="width: 100px;" alt="books">
              <h5 class="card-title">Total Buku</h5>
              <p class="card-text fs-1"><?= htmlspecialchars($total_buku) ?></p>
            </div>
          </div>
        </div>
        <!-- Card 2: Total Manager -->
        <div class="col-sm-6">
          <div class="card bg-danger text-white">
            <div class="card-body">
              <img src="img/team.png" style="width: 100px;" alt="manager">
              <h5 class="card-title">Total Manager</h5>
              <p class="card-text fs-1"><?= htmlspecialchars($total_manager) ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <section id="review">
    <div class="container">
      <div class="row text-center mb-4">
        <div class="col">
          <h1>Review</h1>
        </div>
      </div>
      <div class="row">
        <!-- Review 1 -->
        <div class="col-sm-6 mb-3">
          <div class="card text-black">
            <div class="card-body">
              <h4 class="card-title">Kez</h4>
              <h6 class="text-secondary">Semarang, Jawa Tengah</h6>
              <p>
                "Saya sangat suka dengan komunitas literasi yang ada di RuangBook. Banyak acara diskusi buku dan workshop yang sangat bermanfaat. Selain itu, suasana perpustakaannya sangat tenang, cocok untuk belajar dan mengerjakan tugas."
              </p>
            </div>
          </div>
        </div>
        <!-- Review 2 -->
        <div class="col-sm-6 mb-3">
          <div class="card text-black">
            <div class="card-body">
              <h4 class="card-title">Lina</h4>
              <h6 class="text-secondary">Bandung, Jawa Barat</h6>
              <p>
                "RuangBook benar-benar tempat yang nyaman untuk membaca dan belajar. Koleksi bukunya sangat lengkap, dan sistem pencariannya sangat membantu saya menemukan buku yang saya butuhkan dengan cepat."
              </p>
            </div>
          </div>
        </div>
        <!-- Review 3 -->
        <div class="col-sm-6 mb-3">
          <div class="card text-black">
            <div class="card-body">
              <h4 class="card-title">Mirana</h4>
              <h6 class="text-secondary">Kota Yogyakarta, Yogyakarta</h6>
              <p>
                "Kelebihan RuangBook adalah fasilitas digitalnya. Saya bisa mencari buku secara online sebelum datang ke perpustakaan. Tempat ini juga menyediakan WiFi gratis dan ruang diskusi, yang sangat membantu saya saat bekerja kelompok."
              </p>
            </div>
          </div>
        </div>
        <!-- Review 4 -->
        <div class="col-sm-6 mb-3">
          <div class="card text-black">
            <div class="card-body">
              <h4 class="card-title">Riki</h4>
              <h6 class="text-secondary">Bantul, Yogyakarta</h6>
              <p>
                "RuangBook adalah perpustakaan favorit saya. Selain koleksi bukunya yang lengkap, tempat ini juga memiliki suasana yang mendukung produktivitas. Saya sangat merekomendasikan RuangBook untuk siapa saja yang ingin meningkatkan literasi mereka."
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer bg-dark" id="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h5>Contact Us</h5>
          <ul class="list-unstyled">
            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
              </svg> ruangbook@gmail.com</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
              </svg> 0856 2749 245</li>
            <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
              </svg> Jalan Sudirman No.17, Yogyakarta</li>
          </ul>
        </div>
        <div class="col-md-3">
          <h5>Follow Us</h5>
          <ul class="list-inline footer-links">
            <li class="list-inline-item">
              <a href="https://www.facebook.com/mufid.b.nugroho.1" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                  <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                </svg>
              </a>
            </li>
            <li class="list-inline-item">
              <a href="https://github.com/mufidbahaudin" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                  <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8" />
                </svg>
              </a>
            </li>
            <li class="list-inline-item">
              <a href="https://www.instagram.com/mufidbahaudin_" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                  <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                </svg>
              </a>
            </li>
          </ul>
        </div>
        <div class="col-md-3">
          <h5> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
              <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
            </svg> Jam Buka</h5>
          <ul class="list-unstyled">
            <li>Senin-Jumat : 09.00 - 17.00</li>
            <li>Sabtu : 09.00 - 15.00</li>
          </ul>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-3">
          <p>Copyright &copy;2024. <strong>RuangBook</strong></p>
        </div>
        <div class="col-md-8 text-end">
          <ul class="list-inline footer-links">
            <li class="list-inline-item">
              <a href="#">Privacy Policy</a>
            </li>
            <li class="list-inline-item">
              <a href="#">Terms of Service</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
</body>
<!-- Bootstrap Bundle dengan Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>