<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

// Ambil data statistik
$jumlah_user = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user'"));
$jumlah_selesai = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user' AND status_ujian='selesai'"));
$jumlah_gagal = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user' AND status_ujian='gagal'"));
$jumlah_ujian = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user' AND status_ujian='sedang_ujian'"));

// Ambil data soal
$query_soal = "SELECT * FROM questions ORDER BY created_at DESC";
$result_soal = mysqli_query($conn, $query_soal);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #f8fbff, #ecf1ff);
      min-height: 100vh;
    }

    .navbar {
      background: #4e73df;
      padding: 1rem 2rem;
    }

    .navbar-brand, .nav-link {
      color: #fff !important;
      font-weight: 600;
    }

    .dashboard {
      padding: 40px 20px;
    }

    .card-box {
      background: white;
      border-radius: 20px;
      padding: 25px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      text-align: center;
    }

    .card-box:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .card-icon {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #4e73df;
    }

    .card-title {
      font-size: 1rem;
      font-weight: 600;
      color: #666;
    }

    .card-stat {
      font-size: 1.8rem;
      font-weight: bold;
      color: #2e59d9;
    }

    .btn-action {
      border-radius: 12px;
      font-weight: 500;
      padding: 10px 16px;
    }

    .btn-action:hover {
      opacity: 0.9;
    }

    .table {
      background: white;
      border-radius: 12px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }

    .table th, .table td {
      text-align: center;
    }

    .table th {
      background-color: #4e73df;
      color: white;
    }

    .table .btn-edit, .table .btn-delete {
      transition: all 0.3s ease;
    }

    .table .btn-edit:hover {
      background-color: #28a745;
    }

    .table .btn-delete:hover {
      background-color: #dc3545;
    }
    
    @media (max-width: 576px) {
      .dashboard {
        padding: 20px 10px;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Ujian</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard Content -->
<div class="container dashboard">
  <h3 class="mb-4">👋 Hai, <?= $_SESSION['username'] ?>! Selamat datang di Panel Admin</h3>

  <div class="row g-4 mb-5">
    <div class="col-md-3 col-6">
      <div class="card-box">
        <div class="card-icon">👥</div>
        <div class="card-title">Total Peserta</div>
        <div class="card-stat"><?= $jumlah_user ?></div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card-box">
        <div class="card-icon">📝</div>
        <div class="card-title">Sedang Ujian</div>
        <div class="card-stat"><?= $jumlah_ujian ?></div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card-box">
        <div class="card-icon">✅</div>
        <div class="card-title">Selesai</div>
        <div class="card-stat"><?= $jumlah_selesai ?></div>
      </div>
    </div>
    <div class="col-md-3 col-6">
      <div class="card-box">
        <div class="card-icon">❌</div>
        <div class="card-title">Gagal</div>
        <div class="card-stat"><?= $jumlah_gagal ?></div>
      </div>
    </div>
  </div>

  <!-- Button actions -->
  <div class="d-flex flex-wrap gap-3 mb-4">
    <a href="tambah_soal.php" class="btn btn-primary btn-action">➕ Tambah Soal</a>
    <a href="list_soal.php" class="btn btn-outline-primary btn-action">📄 Lihat Soal</a>
    <a href="notifikasi.php" class="btn btn-outline-danger btn-action">🚨 Cek Notifikasi Curang</a>
  </div>

  <!-- Soal Table -->
  <h4 class="mb-3">Tabel Soal</h4>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Tipe</th>
        <th>Pertanyaan</th>
        <th>Opsi A</th>
        <th>Opsi B</th>
        <th>Opsi C</th>
        <th>Opsi D</th>
        <th>Jawaban</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($soal = mysqli_fetch_assoc($result_soal)): ?>
        <tr>
          <td><?= $soal['id'] ?></td>
          <td><?= $soal['type'] ?></td>
          <td><?= substr($soal['question_text'], 0, 50) . '...' ?></td>
          <td><?= $soal['option_a'] ?></td>
          <td><?= $soal['option_b'] ?></td>
          <td><?= $soal['option_c'] ?></td>
          <td><?= $soal['option_d'] ?></td>
          <td><?= $soal['correct_answer'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
