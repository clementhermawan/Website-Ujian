<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);
$alasan = $data['alasan'] ?? 'Tidak diketahui';

// Ambil nama user dari session, default 'User' jika tidak ada
$nama_user = $_SESSION['username'] ?? 'User';

// Pesan yang akan dikirimkan ke Telegram
$pesan = "🚨 *Kecurangan Terdeteksi!*\n\n👤 User: $nama_user\n📛 Alasan: $alasan\n🕐 Waktu: " . date("Y-m-d H:i:s");

// Ganti TOKEN & CHAT_ID
$token = 'YOUR_TOKEN_ID';
$chat_id = 'YOUR_CHAT_ID';  // Gunakan Chat ID yang sudah kamu dapatkan

// URL untuk kirim pesan ke Telegram bot
$url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($pesan) . "&parse_mode=Markdown";

// Mengirimkan request ke Telegram API
file_get_contents($url);

// Koneksi ke database
include '../config/db.php';

// Simpan log kecurangan ke database
$query = "INSERT INTO cheat_logs (user_id, event, log_time) 
          VALUES ('" . $_SESSION['user_id'] . "', '" . mysqli_real_escape_string($conn, $alasan) . "', NOW())";

if (mysqli_query($conn, $query)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan log ke database']);
}

// Menutup koneksi database
mysqli_close($conn);
?>
