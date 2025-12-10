<?php
session_start();

// Baca password lama dari file
$pwdata = json_decode(file_get_contents('pwpanitia.json'), true);
$current_pw = $pwdata['password'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $oldpw = $_POST['oldpw'];
  $newpw = $_POST['newpw'];
  $confirm = $_POST['confirm'];

  if ($oldpw !== $current_pw) {
    $msg = "<p class='text-red-400 font-medium'>❌ Password lama salah!</p>";
  } elseif ($newpw !== $confirm) {
    $msg = "<p class='text-yellow-400 font-medium'>⚠️ Konfirmasi password tidak cocok!</p>";
  } else {
    file_put_contents('pwpanitia.json', json_encode(["password" => $newpw]));
    $msg = "<p class='text-green-400 font-medium'>✅ Password berhasil diperbarui!</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Password Panitia</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black min-h-screen flex items-center justify-center relative overflow-hidden">

  <!-- Efek cahaya latar belakang -->
  <div class="absolute w-[600px] h-[600px] bg-amber-500/20 blur-[150px] rounded-full top-[-100px] left-[-100px]"></div>
  <div class="absolute w-[400px] h-[400px] bg-purple-500/20 blur-[120px] rounded-full bottom-[-100px] right-[-100px]"></div>

  <div class="relative z-10 bg-gray-900/60 backdrop-blur-2xl border border-gray-700 p-8 rounded-2xl shadow-2xl w-[380px] text-white animate-fadeIn">
    <div class="text-center mb-6">
      <div class="flex justify-center mb-2">
        <img src="osislogo.png" class="w-16 h-16" alt="Logo OSIS">
      </div>
      <h2 class="text-2xl font-bold text-amber-400">Ganti Password</h2>
      <p class="text-gray-400 text-sm">Panitia OSIS SMA Negeri 9 Pandeglang</p>
    </div>

    <?php if(isset($msg)): ?>
      <div class="text-center mb-4"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm text-gray-300 mb-1 font-medium">Password Lama</label>
        <input type="password" name="oldpw" placeholder="Masukkan password lama"
          class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
      </div>

      <div>
        <label class="block text-sm text-gray-300 mb-1 font-medium">Password Baru</label>
        <input type="password" name="newpw" placeholder="Masukkan password baru"
          class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
      </div>

      <div>
        <label class="block text-sm text-gray-300 mb-1 font-medium">Konfirmasi Password Baru</label>
        <input type="password" name="confirm" placeholder="Ulangi password baru"
          class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
      </div>

      <button type="submit"
        class="w-full py-3 bg-amber-500 hover:bg-amber-600 rounded-lg font-semibold shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
        💾 Simpan Perubahan
      </button>

     <a href="index.php"
        class="block text-center text-gray-400 hover:text-amber-400 mt-4 text-sm transition-all duration-300">
        ← Kembali ke Halaman Utama
      </a>
 <a href="panitia.php"
        class="block text-center text-gray-400 hover:text-amber-400 mt-4 text-sm transition-all duration-300">
        ← Kembali ke Halaman Panitia
      </a>
    </form>

    <p class="text-xs text-center text-gray-500 mt-6">Dikelola oleh Sekbid 9 TIK</p>
  </div>

  <style>
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }
    .animate-fadeIn {
      animation: fadeIn 0.6s ease forwards;
    }
  </style>

</body>
</html>