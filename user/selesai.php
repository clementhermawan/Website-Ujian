<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];

// Ambil semua soal yang telah dijawab oleh user
$query = "SELECT ua.answer, q.correct_answer, q.id AS question_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d
          FROM user_answers ua
          JOIN questions q ON ua.question_id = q.id
          WHERE ua.user_id = '$user_id'";
$result = mysqli_query($conn, $query);

$score = 0;  // Nilai awal
$totalQuestions = 0;  // Total soal yang dijawab
$totalCorrect = 0;  // Jumlah soal yang benar

// Array untuk menyimpan soal dan hasil jawaban
$answeredQuestions = [];

// Proses setiap jawaban yang diberikan oleh user
while ($row = mysqli_fetch_assoc($result)) {
    $totalQuestions++;
    $isCorrect = false;

    if ($row['answer'] === $row['correct_answer']) {
        // Jawaban benar
        $score += 4;
        $totalCorrect++;  // Menambah jumlah soal yang benar
        $isCorrect = true;
    } elseif ($row['answer'] !== null) {
        // Jawaban salah (dan bukan kosong)
        $score -= 1;
    }

    // Menyimpan soal dan hasil jawaban
    $answeredQuestions[] = [
        'question_text' => $row['question_text'],
        'answer' => $row['answer'],
        'correct_answer' => $row['correct_answer'],
        'option_a' => $row['option_a'],
        'option_b' => $row['option_b'],
        'option_c' => $row['option_c'],
        'option_d' => $row['option_d'],
        'isCorrect' => $isCorrect
    ];
}

// Menyimpan hasil ujian ke dalam database jika diperlukan
$query = "INSERT INTO results (user_id, score, total_questions, exam_date) 
          VALUES ('$user_id', '$score', '$totalQuestions', NOW())";
mysqli_query($conn, $query);

// Menentukan status kelulusan berdasarkan persentase
$pass_percentage = 0.7;  // 70% jawaban benar
$required_correct_answers = ceil($totalQuestions * $pass_percentage);  // Jumlah soal yang harus dijawab benar untuk lulus
$status = $totalCorrect >= $required_correct_answers ? 'Lulus' : 'Tidak Lulus';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - UTBK</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.5/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Roboto', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #4e73df;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: #fff !important;
        }
        .navbar .navbar-brand {
            font-weight: 600;
            font-size: 1.6rem;
        }
        .container {
            max-width: 900px;
            margin-top: 30px;
        }
        .card {
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .result-header {
            font-size: 2rem;
            font-weight: bold;
            color: #4e73df;
            text-align: center;
        }
        .result-text {
            font-size: 1.1rem;
            margin-top: 10px;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #e74c3c;
        }
        .correct {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .incorrect {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .question-text {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .answer-text {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .answer-text.correct {
            color: white;
        }
        .answer-text.incorrect {
            color: white;
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
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #4e73df;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UTBC - Hasil Ujian</a>
            <div class="d-flex">
                <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Hasil Ujian -->
    <div class="container">
        <h3 class="result-header">Hasil Ujian Anda</h3>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Skor Anda</h5>
                <p class="result-text">Skor akhir Anda: <strong><?= $score ?></strong></p>
                <p class="result-text">Total Soal yang Dijawab: <strong><?= $totalQuestions ?></strong></p>
                <p class="result-text">Status: 
                    <span class="badge <?= $status == 'Lulus' ? 'badge-success' : 'badge-danger' ?>"><?= $status ?></span>
                </p>
            </div>
        </div>

        <!-- Detail Jawaban -->
        <h4 class="result-header">Detail Jawaban Anda:</h4>
        <?php foreach ($answeredQuestions as $question): ?>
            <div class="card">
                <div class="card-body">
                    <p class="question-text"><strong>Soal:</strong> <?= $question['question_text'] ?></p>

                    <!-- Menampilkan Opsi Jawaban -->
                    <p><strong>A:</strong> <?= $question['option_a'] ?></p>
                    <p><strong>B:</strong> <?= $question['option_b'] ?></p>
                    <p><strong>C:</strong> <?= $question['option_c'] ?></p>
                    <p><strong>D:</strong> <?= $question['option_d'] ?></p>

                    <!-- Menampilkan Jawaban yang Dipilih oleh User -->
                    <p class="answer-text <?= $question['isCorrect'] ? 'correct' : 'incorrect' ?>">
                        <strong>Jawaban Anda:</strong> <?= $question['answer'] ?>
                    </p>

                    <!-- Menampilkan Jawaban yang Benar -->
                    <p><strong>Jawaban Benar:</strong> <?= $question['correct_answer'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <a href="../logout.php" class="btn btn-custom mt-3">Selesai</a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2025 UTBC - Ujian Tertulis Buatan Clement - By Develop Clement Hermawan Pelupessy</p>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.5/dist/sweetalert2.min.js"></script>

    <script>
        // Cek status kelulusan dan tampilkan SweetAlert
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($status == 'Lulus'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Selamat!',
                    text: 'Anda Lulus ujian ini! Terus semangat!',
                    confirmButtonText: 'Terima Kasih'
                });
            <?php else: ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Lulus',
                    text: 'Jangan menyerah! Coba lagi dan lebih baik!',
                    confirmButtonText: 'Terima Kasih'
                });
            <?php endif; ?>
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
