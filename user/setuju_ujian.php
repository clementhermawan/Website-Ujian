<?php
session_start();

// Pastikan user sudah login dan berada dalam sesi yang valid
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

// Set session untuk menandakan bahwa user sudah setuju dengan aturan ujian
$_SESSION['setuju_ujian'] = true;

// Mengembalikan status sukses
echo json_encode(['status' => 'success']);
?>
