<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($username === 'pramuka' && $password === 'TanyaAbmas') {
    $_SESSION['admin'] = true;
    header("Location: index.php");
    exit;
  } else {
    $error = "Username atau password salah!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <style>
    body {
      background: #1e1e2f;
      color: #f1f1f1;
      f{ont-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
    }
    .container {
      max-width: 300px;
      margin: 80px auto;
      padding: 40px;
      background: #2a2a3d;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
      text-align: center;
    }
    h2 {
      margin-bottom: 20px;
      color: #61dafb;
    }
    input[type="text"], input[type="password"] {
      width: 80%;
      padding: 12px;
      margin-bottom: 14px;
      border: none;
      border-radius: 6px;
      background: #44475a;
      color: #fff;
    }
    button {
      width: 80%;
      padding: 12px;
      background: #61dafb;
      color: #1e1e2f;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }
      .btn-secondary {
      background: #d11d1d
      border: none;
      color: #bbb;
      font-size: 14px;
      padding: 10px 100px;
      margin-top: 10px;
      display: inline-block;
      border-radius: 10px;
      transition: all 0.2s ease;
      text-decoration: none;
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #fff;
      border-color: #00d4ff;
    }
    .error {
      color: #ff6b6b;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Admin Login</h2>
    <?php if (!empty($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
      <a href="index.php" class="btn-secondary" onclick="navigateWithTransition(event)">Cancel</a>
  </div>
</body>
</html>