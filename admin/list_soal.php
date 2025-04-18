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

// Query untuk mendapatkan semua soal
$query = "SELECT * FROM questions ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
}

// Hitung jumlah soal
$jumlah_soal = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>List Soal - Admin</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <!-- Custom Styles -->
  <style>
    body {
      background-color: #f0f4f8;
      font-family: 'Roboto', sans-serif;
      color: #333;
    }
    .navbar {
      background-color: #2c3e50;
    }
    .navbar a {
      color: #fff !important;
    }
    .navbar .navbar-brand {
      font-weight: 600;
      font-size: 1.4rem;
    }
    .navbar .btn {
      color: #fff !important;
      border-radius: 8px;
      font-size: 0.9rem;
    }
    .navbar .btn:hover {
      background-color: #e74c3c;
      transform: scale(1.05);
    }
    .dashboard {
      margin-top: 30px;
    }
    .card-box {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      padding: 25px;
      text-align: center;
      transition: all 0.3s ease-in-out;
    }
    .card-box:hover {
      transform: translateY(-10px);
    }
    .card-title {
      font-weight: bold;
      font-size: 1.3rem;
      color: #333;
    }
    .card-stat {
      font-size: 2rem;
      color: #3498db;
    }
    .btn-custom {
      background-color: #3498db;
      color: white;
      border-radius: 12px;
      padding: 10px 20px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .btn-custom:hover {
      background-color: #2980b9;
      transform: scale(1.05);
    }
    .header-title {
      font-size: 1.5rem;
      font-weight: bold;
      color: #333;
    }
    .table thead {
      background-color: #3498db;
      color: white;
    }
    .table-striped tbody tr:nth-child(odd) {
      background-color: #ecf0f1;
    }
    .table-hover tbody tr:hover {
      background-color: #e1ecf4;
    }
    .table-bordered td, .table-bordered th {
      border: 1px solid #dfe6e9;
    }
    .btn-sm {
      font-size: 0.85rem;
      padding: 6px 12px;
      border-radius: 8px;
    }
    .table-container {
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Admin Ujian</a>
      <div class="d-flex">
        <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Dashboard Content -->
  <div class="container dashboard">
    <h3 class="mb-4 text-center text-dark">👋 Hai, <?= $_SESSION['username'] ?>! Selamat datang di Panel Admin</h3>

    <!-- Statistics Row -->
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

    <!-- Action Buttons -->
    <div class="d-flex flex-wrap gap-3 mb-4 justify-content-center">
      <a href="tambah_soal.php" class="btn btn-custom">➕ Tambah Soal</a>
      <a href="list_soal.php" class="btn btn-outline-primary btn-custom">📄 Lihat Soal</a>
      <a href="notifikasi.php" class="btn btn-outline-danger btn-custom">🚨 Cek Notifikasi Curang</a>
    </div>

    <!-- Table Section -->
    <div class="table-container">
      <div class="card">
        <div class="card-body">
          <h4 class="header-title mb-4">Daftar Soal (<?= $jumlah_soal ?> soal)</h4>
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Soal</th>
                <th>Tipe</th>
                <th>Waktu (detik)</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>";
                  echo "<td>" . $no++ . "</td>";
                  echo "<td>" . htmlspecialchars($row['question_text']) . "</td>";
                  echo "<td>" . ($row['type'] == 'pg' ? 'Pilihan Ganda' : 'Isian Singkat') . "</td>";
                  echo "<td>" . ($row['is_panjang'] == 1 ? '2 menit' : '1 menit') . "</td>";
                  echo "<td>" . $row['created_at'] . "</td>";
                  echo "<td>
                          <a href='edit_soal.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                          <a href='hapus_soal.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus soal?\")'>Hapus</a>
                        </td>";
                  echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
