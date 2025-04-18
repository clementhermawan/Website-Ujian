<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

include '../config/db.php';

if (!isset($_GET['id'])) {
  header('Location: notifikasi.php');
  exit();
}

$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header('Location: notifikasi.php');
  exit();
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pengguna - Admin</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom Styles -->
  <style>
    body {
      background-color: #f0f4f8;
      font-family: 'Roboto', sans-serif;
    }
    .container {
      max-width: 800px;
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <div class="container">
    <h3 class="mb-4">Detail Pengguna ID: <?= $user['id'] ?></h3>

    <table class="table table-bordered">
      <tr>
        <th>Nama Pengguna</th>
        <td><?= htmlspecialchars($user['username']) ?></td>
      </tr>
      <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($user['email']) ?></td>
      </tr>
      <tr>
        <th>Role</th>
        <td><?= htmlspecialchars($user['role']) ?></td>
      </tr>
      <!-- Add more fields as needed -->
    </table>

    <a href="notifikasi.php" class="btn btn-secondary">Kembali ke Notifikasi</a>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
