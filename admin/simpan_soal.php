<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

include '../config/db.php';

// Ambil data dari form
$tipe_soal = $_POST['tipe'];
$soal = $_POST['soal'];
$waktu = $_POST['waktu'];

// Validasi input
if (empty($tipe_soal) || empty($soal)) {
  echo "Tipe soal dan soal tidak boleh kosong!";
  exit();
}

// Cek apakah soal panjang
$is_panjang = isset($_POST['is_panjang']) ? 1 : 0;

if ($tipe_soal === 'pg') {
  // Ambil data untuk pilihan ganda
  $opsi_a = $_POST['opsi_a'];
  $opsi_b = $_POST['opsi_b'];
  $opsi_c = $_POST['opsi_c'];
  $opsi_d = $_POST['opsi_d'];
  $jawaban_pg = $_POST['jawaban_pg'];

  // Validasi pilihan dan jawaban
  if (empty($opsi_a) || empty($opsi_b) || empty($opsi_c) || empty($opsi_d) || empty($jawaban_pg)) {
    echo "Semua pilihan dan jawaban harus diisi!";
    exit();
  }

  // Simpan soal pilihan ganda ke database
  $query = "INSERT INTO questions (type, question_text, option_a, option_b, option_c, option_d, correct_answer, is_panjang, created_at) 
            VALUES ('$tipe_soal', '$soal', '$opsi_a', '$opsi_b', '$opsi_c', '$opsi_d', '$jawaban_pg', '$is_panjang', NOW())";
} else if ($tipe_soal === 'isian') {
  // Ambil data untuk soal isian singkat
  $jawaban_isian = $_POST['jawaban_isian'];

  // Validasi jawaban isian
  if (empty($jawaban_isian)) {
    echo "Jawaban isian tidak boleh kosong!";
    exit();
  }

  // Simpan soal isian ke database
  $query = "INSERT INTO questions (type, question_text, correct_answer, is_panjang, created_at) 
            VALUES ('$tipe_soal', '$soal', '$jawaban_isian', '$is_panjang', NOW())";
} else {
  echo "Tipe soal tidak valid!";
  exit();
}

// Eksekusi query untuk menyimpan soal
if (mysqli_query($conn, $query)) {
  // Redirect ke halaman list soal setelah sukses
  header("Location: list_soal.php");
} else {
  echo "Terjadi kesalahan: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
