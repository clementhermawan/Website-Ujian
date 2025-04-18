<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

include '../config/db.php';

// Ambil notifikasi yang tercatat (misalnya event yang terkait dengan curang)
$query = "SELECT * FROM cheat_logs ORDER BY log_time DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifikasi Curang - Admin</title>
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
    .container {
      max-width: 900px;
      margin-top: 30px;
    }
    .card {
      margin-bottom: 20px;
    }
    .log-time {
      font-size: 0.8rem;
      color: #999;
    }
    .badge-danger {
      background-color: #e74c3c;
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

  <!-- Notifikasi Curang -->
  <div class="container">
    <h3 class="mb-4">🚨 Notifikasi Curang</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Peserta ID: <?= $row['user_id'] ?></h5>
            <p class="card-text">
              <strong>Event:</strong> <?= htmlspecialchars($row['event']) ?><br>
              <span class="log-time">Terjadi pada: <?= $row['log_time'] ?></span>
            </p>
            <a href="user_detail.php?id=<?= $row['user_id'] ?>" class="btn btn-info btn-sm">Lihat Detail</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-warning">
        Tidak ada notifikasi curang saat ini.
      </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
