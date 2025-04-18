# UTBC - Ujian Tertulis Buatan Clement

Aplikasi Ujian Online berbasis Web yang dikembangkan dengan PHP dan MySQL. Proyek ini dibuat untuk menyelenggarakan ujian berbasis komputer secara sederhana namun fungsional. Cocok digunakan dalam skala kecil seperti latihan UTBK, tryout sekolah, maupun tes internal.

## 🚀 Fitur Utama

- ✅ Autentikasi user (login & logout)
- 📋 Soal pilihan ganda dari database MySQL
- ✍️ Menjawab soal dan menyimpan jawaban secara otomatis
- 🧠 Penilaian otomatis berdasarkan jawaban benar/salah
- 📊 Menampilkan skor akhir dan status kelulusan
- 🧾 Detail jawaban dan pembanding jawaban benar
- 🎉 SweetAlert popup untuk ucapan selamat atau penyemangat
- 🔐 Hasil ujian tersimpan di database untuk analisis lebih lanjut

## 📁 Struktur Folder

/config  
 └── db.php  // Koneksi ke database  
/pages  
 └── soal.php  // Halaman pengerjaan ujian  
 └── hasil.php  // Halaman hasil ujian  
 └── login.php  // Halaman login user  
 └── logout.php  // Proses logout  
/assets  
 └── (opsional: CSS, JS, gambar)  
README.md  
index.php  

## ⚙️ Teknologi yang Digunakan

- PHP (Plain PHP tanpa framework)
- MySQL/MariaDB
- Bootstrap 5 (styling)
- SweetAlert2 (popup notifikasi)
- JavaScript (event handling)

## 🧪 Cara Menjalankan (Secara Lokal)

1. **Clone repo ini**  
   git clone https://github.com/clementhermawan/Website-Ujian.git  
   cd utbc-ujian-online

2. **Import Database**
   - Buka phpMyAdmin atau gunakan MySQL CLI
   - Import file `database.sql` (pastikan kamu punya file ini)

3. **Atur koneksi database**
   - Edit file `config/db.php` dan sesuaikan dengan konfigurasi host, user, password, dan nama database kamu

4. **Jalankan di localhost**
   - Letakkan project di folder `htdocs` (XAMPP) atau server lokal kamu
   - Akses di browser: `http://localhost/utbc-ujian-online/pages/login.php`

## 🎯 Penilaian & Status

- Jawaban benar: +4 poin
- Jawaban salah: -1 poin
- Jawaban kosong: 0 poin
- Kelulusan: Minimal 70% jawaban benar

## 🧠 SweetAlert Notifikasi

- Jika **Lulus**: Popup ucapan *Selamat!*
- Jika **Tidak Lulus**: Popup penyemangat untuk mencoba lagi

## 🙋‍♂️ Tentang Pengembang

**Nama:** Clement Hermawan Pelupessy  
**Role:** Fullstack Developer | Backend Enthusiast   
**GitHub:** https://github.com/clementhermawan

## 📄 Lisensi

MIT License. Silakan gunakan, modifikasi, dan kembangkan dengan bebas. [LICENSE](https://github.com/clementhermawan/Licensi)

---

> Develop oleh Clement Hermawan Pelupessy - 2025
