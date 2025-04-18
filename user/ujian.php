<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

$user_id = $_SESSION['user_id'];

// Ambil ID soal saat ini dari parameter atau default ke soal pertama
$current_question_id = isset($_GET['q']) ? (int)$_GET['q'] : null;

// Jika belum, ambil soal pertama yang belum dijawab
if (!$current_question_id) {
    $query = "SELECT q.id FROM questions q
              LEFT JOIN user_answers ua ON q.id = ua.question_id AND ua.user_id = '$user_id'
              WHERE ua.answer IS NULL
              ORDER BY q.id ASC LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $current_question_id = $row['id'];
    } else {
        header("Location: selesai.php"); // Semua soal sudah dijawab
        exit();
    }
}

// Ambil data soal
$query = "SELECT * FROM questions WHERE id = '$current_question_id'";
$result = mysqli_query($conn, $query);
$soal = mysqli_fetch_assoc($result);

// Cek jika tidak ditemukan
if (!$soal) {
    echo "Soal tidak ditemukan.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ujian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4">
            <h5 class="mb-3">Soal:</h5>
            <form method="POST" action="submit_jawaban.php">
                <input type="hidden" name="question_id" value="<?= $soal['id'] ?>">
                <p><?= $soal['question_text'] ?></p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="A" value="A" required>
                    <label class="form-check-label" for="A"><?= $soal['option_a'] ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="B" value="B">
                    <label class="form-check-label" for="B"><?= $soal['option_b'] ?></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="C" value="C">
                    <label class="form-check-label" for="C"><?= $soal['option_c'] ?></label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="answer" id="D" value="D">
                    <label class="form-check-label" for="D"><?= $soal['option_d'] ?></label>
                </div>
                <button type="submit" class="btn btn-primary">Jawab & Lanjut</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let cheatingDetected = false;
        let googleNotificationDetected = false;
        let lastBlurTime = 0;
        let lastFocusTime = 0;
        let popUpDetectedTime = 0; // Waktu terakhir terdeteksi pop-up (Google)

        const popupDelayThreshold = 2000; // 2 detik, batas waktu untuk menentukan apakah pop-up berasal dari Google

        // Fungsi untuk melaporkan kecurangan
        function laporkanKecurangan(alasan) {
            if (cheatingDetected) return; // Mencegah pengiriman notifikasi ganda
            cheatingDetected = true;

            // Kirim data kecurangan ke server
            fetch('lapor_kecurangan.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    alasan: alasan
                })
            }).then(() => {
                // Tampilkan peringatan SweetAlert dan logout
                Swal.fire({
                    icon: 'error',
                    title: 'Kecurangan Terdeteksi',
                    text: alasan,
                    allowOutsideClick: false
                }).then(() => {
                    window.location.href = '../logout.php'; // Logout pengguna
                });
            });
        }

        // Cek apakah itu pop-up dari Google
        function isGooglePopup() {
            // Mengecek apakah judul halaman berubah ke sesuatu yang terkait dengan Google (contoh: Change Password)
            if (document.title.includes("Change your password") || document.title.includes("Google Account")) {
                return true;
            }

            // Menggunakan sebuah waktu untuk membedakan event deteksi dari pop-up Google
            const currentTime = new Date().getTime();
            if (currentTime - popUpDetectedTime < popupDelayThreshold) {
                return true;
            }

            return false;
        }

        // Fungsi untuk menangani kehilangan fokus halaman
        window.addEventListener('blur', function() {
            const currentTime = new Date().getTime();

            // Jika deteksi blur terlalu cepat, kemungkinan karena pop-up atau aplikasi luar
            if (currentTime - lastBlurTime < 1000) {
                return;
            }

            // Deteksi pop-up Google berdasarkan waktu
            if (isGooglePopup()) {
                googleNotificationDetected = true;
                return; // Abaikan jika itu adalah pop-up Google
            }

            lastBlurTime = currentTime;
            laporkanKecurangan("Meninggalkan jendela ujian.");
        });

        // Fungsi untuk menangani perubahan visibilitas halaman (berpindah tab)
        document.addEventListener("visibilitychange", function() {
            if (document.hidden) {
                const currentTime = new Date().getTime();

                // Jika deteksi perubahan visibilitas terlalu cepat, kemungkinan besar karena pop-up sistem
                if (currentTime - popUpDetectedTime < popupDelayThreshold) {
                    googleNotificationDetected = true;
                    return; // Abaikan jika itu adalah pop-up Google
                }

                if (isGooglePopup()) {
                    googleNotificationDetected = true;
                    return;
                }

                laporkanKecurangan("Berpindah tab atau keluar dari halaman ujian.");
            }
        });

        // Fungsi untuk menangani focus kembali pada halaman
        window.addEventListener('focus', function() {
            const currentTime = new Date().getTime();

            if (document.hidden || googleNotificationDetected) return; // Jangan deteksi jika halaman sudah keluar atau pop-up terdeteksi

            // Cek apakah ada perubahan fokus halaman yang terdeteksi dalam waktu singkat
            if (currentTime - lastFocusTime < 1000) {
                return;
            }

            lastFocusTime = currentTime;
            console.log("Jendela kembali fokus, periksa status.");
        });

        // Memantau elemen yang muncul di halaman dan mengecek apakah itu pop-up dari Google
        setInterval(function() {
            const currentTime = new Date().getTime();

            // Memeriksa apakah ada elemen pop-up dari Google muncul
            if (document.querySelector('.google-popup') || document.querySelector('.notification-popup')) {
                popUpDetectedTime = currentTime; // Menyimpan waktu deteksi pop-up
            }
        }, 500); // Cek setiap setengah detik
    </script>
</body>

</html>