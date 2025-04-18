<?php
session_start();
include 'config/db.php';

$username = $_POST['username'];
$password = md5($_POST['password']); // sesuai struktur db

$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if ($data) {
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    if ($data['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/ujian.php");
    }
} else {
    $_SESSION['error'] = "Username atau Password salah!";
    header("Location: login.php");
}
