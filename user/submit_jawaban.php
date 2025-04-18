<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$question_id = $_POST['question_id'];
$answer = $_POST['answer'];

// Simpan jawaban ke database
$query = "REPLACE INTO user_answers (user_id, question_id, answer) VALUES ('$user_id', '$question_id', '$answer')";
mysqli_query($conn, $query);

// Ambil soal selanjutnya
$next_query = "SELECT q.id FROM questions q
               LEFT JOIN user_answers ua ON q.id = ua.question_id AND ua.user_id = '$user_id'
               WHERE ua.answer IS NULL ORDER BY q.id ASC LIMIT 1";
$result = mysqli_query($conn, $next_query);

if ($next = mysqli_fetch_assoc($result)) {
    header("Location: ujian.php?q=" . $next['id']);
} else {
    header("Location: selesai.php");
}
exit();
