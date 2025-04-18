<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

include '../config/db.php';

// Pastikan ada parameter id dalam URL
if (!isset($_GET['id'])) {
  header('Location: list_soal.php');
  exit();
}

$id = $_GET['id'];

// Ambil data soal dari database
$query = "SELECT * FROM questions WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header('Location: list_soal.php');
  exit();
}

$soal = mysqli_fetch_assoc($result);

// Update soal saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $question_text = mysqli_real_escape_string($conn, $_POST['question_text']);
  $type = $_POST['type'];
  $option_a = isset($_POST['option_a']) ? mysqli_real_escape_string($conn, $_POST['option_a']) : NULL;
  $option_b = isset($_POST['option_b']) ? mysqli_real_escape_string($conn, $_POST['option_b']) : NULL;
  $option_c = isset($_POST['option_c']) ? mysqli_real_escape_string($conn, $_POST['option_c']) : NULL;
  $option_d = isset($_POST['option_d']) ? mysqli_real_escape_string($conn, $_POST['option_d']) : NULL;
  $correct_answer = mysqli_real_escape_string($conn, $_POST['correct_answer']);
  $is_panjang = $_POST['is_panjang'];

  $update_query = "UPDATE questions SET 
    question_text = '$question_text', 
    type = '$type', 
    option_a = '$option_a', 
    option_b = '$option_b', 
    option_c = '$option_c', 
    option_d = '$option_d', 
    correct_answer = '$correct_answer',
    is_panjang = '$is_panjang'
    WHERE id = $id";

  if (mysqli_query($conn, $update_query)) {
    header('Location: list_soal.php');
    exit();
  } else {
    echo "Error updating record: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Soal - Admin</title>
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
    .form-container {
      max-width: 800px;
      margin: 40px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
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

  <!-- Form Edit Soal -->
  <div class="container form-container">
    <h3 class="text-center mb-4">Edit Soal</h3>

    <form method="POST">
      <div class="mb-3">
        <label for="question_text" class="form-label">Teks Soal</label>
        <textarea class="form-control" id="question_text" name="question_text" rows="3"><?= htmlspecialchars($soal['question_text']) ?></textarea>
      </div>

      <div class="mb-3">
        <label for="type" class="form-label">Tipe Soal</label>
        <select class="form-select" id="type" name="type">
          <option value="pg" <?= ($soal['type'] == 'pg') ? 'selected' : '' ?>>Pilihan Ganda</option>
          <option value="isian" <?= ($soal['type'] == 'isian') ? 'selected' : '' ?>>Isian Singkat</option>
        </select>
      </div>

      <!-- Pilihan Ganda Options -->
      <?php if ($soal['type'] == 'pg') { ?>
        <div class="mb-3">
          <label for="option_a" class="form-label">Pilihan A</label>
          <input type="text" class="form-control" id="option_a" name="option_a" value="<?= htmlspecialchars($soal['option_a']) ?>">
        </div>
        <div class="mb-3">
          <label for="option_b" class="form-label">Pilihan B</label>
          <input type="text" class="form-control" id="option_b" name="option_b" value="<?= htmlspecialchars($soal['option_b']) ?>">
        </div>
        <div class="mb-3">
          <label for="option_c" class="form-label">Pilihan C</label>
          <input type="text" class="form-control" id="option_c" name="option_c" value="<?= htmlspecialchars($soal['option_c']) ?>">
        </div>
        <div class="mb-3">
          <label for="option_d" class="form-label">Pilihan D</label>
          <input type="text" class="form-control" id="option_d" name="option_d" value="<?= htmlspecialchars($soal['option_d']) ?>">
        </div>
      <?php } ?>

      <div class="mb-3">
        <label for="correct_answer" class="form-label">Jawaban yang Benar</label>
        <input type="text" class="form-control" id="correct_answer" name="correct_answer" value="<?= htmlspecialchars($soal['correct_answer']) ?>">
      </div>

      <div class="mb-3">
        <label for="is_panjang" class="form-label">Waktu Soal</label>
        <select class="form-select" id="is_panjang" name="is_panjang">
          <option value="1" <?= ($soal['is_panjang'] == 1) ? 'selected' : '' ?>>2 Menit</option>
          <option value="0" <?= ($soal['is_panjang'] == 0) ? 'selected' : '' ?>>1 Menit</option>
        </select>
      </div>

      <button type="submit" class="btn btn-custom">Simpan Perubahan</button>
    </form>

    <a href="list_soal.php" class="btn btn-secondary btn-sm mt-3">Kembali ke Daftar Soal</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
