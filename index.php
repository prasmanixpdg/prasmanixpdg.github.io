<?php
session_start();
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// Lokasi file password panitia
$pw_file = 'pw_panitia.secret';

// Fungsi untuk update password panitia
function update_panitia_password($new_pw) {
    global $pw_file;
    $safe_pw = trim($new_pw);
    file_put_contents($pw_file, $safe_pw);
}

// Jika admin mengirim password baru
if ($is_admin && isset($_POST['new_pw_panitia'])) {
    update_panitia_password($_POST['new_pw_panitia']);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Twibbon SMANIX</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #00d4ff;
      --primary-dark: #007bff;
      --bg-dark: #0e0e10;
      --card-bg: rgba(255, 255, 255, 0.05);
      --border: rgba(255, 255, 255, 0.1);
      --text-light: #f0f0f0;
      --text-muted: #a0a0a0;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, var(--bg-dark), #1a1a1c);
      color: var(--text-light);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: fadeIn 0.6s ease forwards;
      overflow: hidden;
    }

    .container {
      background: var(--card-bg);
      backdrop-filter: blur(15px);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 50px 40px;
      width: 90%;
      max-width: 450px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.4);
      animation: slideUp 0.6s ease forwards;
    }

    h1 {
      font-size: 30px;
      color: var(--primary);
      margin-bottom: 8px;
      font-weight: 600;
    }

    p {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 30px;
    }

    .btn {
      display: block;
      width: 100%;
      padding: 14px;
      margin-bottom: 16px;
      font-size: 16px;
      font-weight: 500;
      border-radius: 12px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 212, 255, 0.4);
    }

    .btn-secondary {
      background: none;
      border: 1px solid #555;
      color: #bbb;
      font-size: 14px;
      padding: 10px 16px;
      border-radius: 10px;
      transition: all 0.2s ease;
      display: inline-block;
      margin-top: 10px;
    }

    .btn-secondary:hover {
      border-color: var(--primary);
      color: white;
    }

    .footer {
      margin-top: 30px;
      font-size: 12px;
      color: #888;
      letter-spacing: 0.5px;
    }

    /* Modal */
    .modal {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.6);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 999;
      animation: fadeIn 0.3s ease forwards;
    }

    .modal-content {
      background: #1c1c1f;
      border-radius: 16px;
      padding: 25px;
      width: 90%;
      max-width: 340px;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }

    .modal-content h2 {
      color: var(--primary);
      margin-bottom: 14px;
      font-size: 20px;
    }

    .modal-content input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #444;
      background: #2b2b2e;
      color: white;
      margin-bottom: 12px;
      outline: none;
    }

    .modal-content button {
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px;
      width: 100%;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .modal-content button:hover {
      background: var(--primary-dark);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>PORTAL TWIBBON</h1>
    <p>Pilih menu yang ingin kamu gunakan di bawah ini.</p>

    <a href="peserta.html" class="btn" onclick="navigateWithTransition(event)">🎓 Twibbon Peserta</a>
    <a href="panitia.php" class="btn" onclick="navigateWithTransition(event)">🛠️ Twibbon Panitia</a>
    <a href="caption.php" class="btn" onclick="navigateWithTransition(event)">📋 Caption</a>

    <?php if ($is_admin): ?>
      <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 25px 0;">
      <strong>ADMIN MENU</strong>
      <a href="upload_frame.php" class="btn">🖼️ Kelola Frame</a>
      <a href="edit_caption.php" class="btn">📝 Edit Caption</a>
      <a href="editpwpanitia.php" class="btn">🔐 Ubah Password Panitia</a>
      <a href="logout.php" class="btn-secondary">🚪 Logout Admin</a>
    <?php else: ?>
      <a href="login.php" class="btn-secondary">🔐 Login Admin</a>
    <?php endif; ?>

    <div class="footer">
      © 2025 Sekbid 9 TIK - OSIS SMANIX Pandeglang<br>
      Created by <strong>Atoillah</strong>
    </div>
  </div>

  <!-- Modal Edit Password -->
  <div class="modal" id="pwModal">
    <div class="modal-content">
      <h2>Ganti Password Panitia</h2>
      <form method="post">
        <input type="password" name="new_pw_panitia" placeholder="Password baru..." required />
        <button type="submit">Simpan Perubahan</button>
      </form>
    </div>
  </div>

  <script>
    function navigateWithTransition(event) {
      event.preventDefault();
      const target = event.currentTarget.getAttribute("href");
      document.body.style.animation = "fadeOut 0.4s forwards";
      setTimeout(() => { window.location.href = target; }, 400);
    }

    function openModal() {
      document.getElementById('pwModal').style.display = 'flex';
    }

    // Klik di luar modal untuk menutup
    window.onclick = function(e) {
      const modal = document.getElementById('pwModal');
      if (e.target === modal) modal.style.display = 'none';
    }
  </script>
</body>
</html>