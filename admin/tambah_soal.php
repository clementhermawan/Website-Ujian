<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Soal - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #f4f7ff, #e6ecff);
      padding: 40px 20px;
    }

    .form-box {
      background: white;
      border-radius: 20px;
      padding: 30px;
      max-width: 700px;
      margin: auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      animation: fadeIn 0.8s ease;
    }

    h4 {
      font-weight: 600;
      margin-bottom: 25px;
      color: #333;
    }

    .form-label {
      font-weight: 500;
      color: #555;
    }

    .form-control, .form-select {
      border-radius: 12px;
      padding: 10px 15px;
    }

    .btn-submit {
      background-color: #4e73df;
      color: white;
      font-weight: 500;
      border-radius: 12px;
      padding: 10px 20px;
      transition: all 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #2e59d9;
    }

    .btn-back {
      background-color: #6c757d;
      color: white;
      font-weight: 500;
      border-radius: 12px;
      padding: 10px 20px;
      transition: all 0.3s ease;
    }

    .btn-back:hover {
      background-color: #5a6268;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(30px);}
      to {opacity: 1; transform: translateY(0);}
    }

    .form-extra {
      display: none;
    }
  </style>

  <script>
    function toggleFormType() {
      const type = document.getElementById('tipe_soal').value;
      document.querySelectorAll('.form-extra').forEach(div => div.style.display = 'none');

      if (type === 'pg') {
        document.getElementById('pg-form').style.display = 'block';
      } else if (type === 'isian') {
        document.getElementById('isian-form').style.display = 'block';
      }
    }

    function cekPanjang() {
      const soal = document.getElementById('soal').value;
      const waktu = document.getElementById('waktu');

      if (soal.length > 120) {
        waktu.value = 120; // 2 menit
      } else {
        waktu.value = 60; // 1 menit
      }
    }
  </script>
</head>
<body>

<div class="form-box">
  <h4>➕ Tambah Soal Baru</h4>

  <!-- Kembali Button -->
  <div class="mb-3">
    <a href="dashboard.php" class="btn btn-back">◀️ Kembali ke Dashboard</a>
  </div>

  <form action="simpan_soal.php" method="POST">
    <div class="mb-3">
      <label class="form-label">Tipe Soal</label>
      <select name="tipe" id="tipe_soal" class="form-select" onchange="toggleFormType()" required>
        <option value="">Pilih Tipe</option>
        <option value="pg">Pilihan Ganda</option>
        <option value="isian">Isian Singkat</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Isi Soal</label>
      <textarea name="soal" id="soal" class="form-control" rows="4" oninput="cekPanjang()" required></textarea>
    </div>

    <div class="form-extra" id="pg-form">
      <div class="mb-2"><label class="form-label">Pilihan A</label><input type="text" name="opsi_a" class="form-control"></div>
      <div class="mb-2"><label class="form-label">Pilihan B</label><input type="text" name="opsi_b" class="form-control"></div>
      <div class="mb-2"><label class="form-label">Pilihan C</label><input type="text" name="opsi_c" class="form-control"></div>
      <div class="mb-2"><label class="form-label">Pilihan D</label><input type="text" name="opsi_d" class="form-control"></div>
      <div class="mb-3">
        <label class="form-label">Jawaban Benar</label>
        <select name="jawaban_pg" class="form-select">
          <option value="">Pilih Jawaban</option>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>
      </div>
    </div>

    <div class="form-extra" id="isian-form">
      <div class="mb-3">
        <label class="form-label">Jawaban Benar</label>
        <input type="text" name="jawaban_isian" class="form-control">
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label">Waktu (detik)</label>
      <input type="number" name="waktu" id="waktu" class="form-control" readonly value="60">
      <small class="text-muted">Sistem otomatis memberikan 2 menit jika soal panjang.</small>
    </div>

    <button type="submit" class="btn btn-submit w-100">💾 Simpan Soal</button>
  </form>
</div>

</body>
</html>
