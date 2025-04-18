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

// Hapus jawaban terkait soal terlebih dahulu di tabel user_answers
$delete_answers_query = "DELETE FROM user_answers WHERE question_id = $id";
mysqli_query($conn, $delete_answers_query);

// Hapus soal dari tabel questions
$delete_query = "DELETE FROM questions WHERE id = $id";

if (mysqli_query($conn, $delete_query)) {
  header('Location: list_soal.php');
  exit();
} else {
  echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
