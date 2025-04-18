<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Ujian UTBK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #eef2f3, #c3cfe2);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
      padding: 40px 30px;
      width: 100%;
      max-width: 400px;
      animation: fadeIn 1s ease-in-out;
    }

    .login-card h4 {
      font-weight: 600;
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }

    .form-label {
      font-weight: 500;
      color: #444;
    }

    .form-control {
      border-radius: 12px;
      padding: 10px 15px;
    }

    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
      border-color: #007bff;
    }

    .btn-login {
      background-color: #007bff;
      color: white;
      font-weight: 500;
      border-radius: 12px;
      padding: 10px;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background-color: #0056b3;
    }

    .branding {
      text-align: center;
      font-size: 1rem;
      color: #888;
      margin-bottom: 10px;
    }

    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="login-card">
  <div class="branding">📝 Ujian Tertulis Buatan Clement</div>
  <h4>Login Peserta UTBC</h4>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form method="POST" action="auth.php">
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" required autofocus>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-login w-100 mt-2">Masuk</button>
  </form>
</div>

</body>
</html>
