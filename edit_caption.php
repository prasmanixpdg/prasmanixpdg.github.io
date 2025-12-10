<?php
session_start();
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
if (!$is_admin) {
  header("Location: login.php");
  exit;
}

$file = "caption.json";
if (!file_exists($file)) file_put_contents($file, "[]");

$captions = json_decode(file_get_contents($file), true);
if (!is_array($captions)) $captions = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $captions = $_POST['captions'] ?? [];
  file_put_contents($file, json_encode($captions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  header("Location: edit_caption.php?saved=1");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Template Caption</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: #0f172a;
    color: #f8fafc;
    min-height: 100vh;
  }
  .glass {
    background: rgba(255, 255, 255, 0.06);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
  }
  .expand {
    transition: all 0.3s ease;
  }
  .fab {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 56px;
    height: 56px;
    background: #0ea5e9;
    border-radius: 50%;
    color: white;
    font-size: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: background 0.3s ease;
  }
  .fab:hover {
    background: #0284c7;
  }
</style>
</head>
<body class="px-4 py-10 flex justify-center">

<div class="w-full max-w-2xl">
  <h1 class="text-2xl font-semibold text-center text-sky-400 mb-6">📝 Template Caption</h1>

  <?php if (isset($_GET['saved'])): ?>
    <div class="bg-green-600/20 text-green-300 px-4 py-3 rounded-lg text-center mb-4 border border-green-500/30">
      ✅ Template berhasil disimpan!
    </div>
  <?php endif; ?>

  <form method="POST" id="captionForm" class="space-y-4">
    <div id="captionList" class="space-y-3"></div>

    <div class="text-center mt-8">
      <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">💾 Simpan Perubahan</button>
    </div>
  </form>

  <div class="text-center mt-5">
    <a href="index.php" class="text-sky-400 hover:text-sky-300 underline">⬅ Kembali ke Beranda</a>
  </div>
</div>

<!-- Tombol tambah (+) melayang -->
<div class="fab" onclick="addCaption()">+</div>

<script>
let captions = <?= json_encode($captions, JSON_UNESCAPED_UNICODE) ?>;
const list = document.getElementById("captionList");

function render() {
  list.innerHTML = "";
  captions.forEach((c, i) => {
    const id = `cap${i}`;
    list.innerHTML += `
      <div class="glass rounded-xl p-4 expand" data-index="${i}">
        <button type="button" onclick="toggleExpand('${id}')" class="w-full text-left font-semibold text-lg text-sky-300 flex justify-between items-center">
          <span>${escapeHtml(c.title || 'Tanpa Judul')}</span>
          <span id="${id}-arrow" class="transition-transform">▼</span>
        </button>

        <div id="${id}-content" class="hidden mt-3 space-y-2">
          <label class="block text-sm">Judul:</label>
          <input type="text" name="captions[${i}][title]" value="${escapeHtml(c.title || '')}" class="w-full px-3 py-2 bg-slate-800 rounded-lg border border-white/10 focus:ring-2 focus:ring-sky-400 outline-none text-white">

          <label class="block text-sm">Isi Template:</label>
          <textarea name="captions[${i}][content]" rows="3" class="w-full px-3 py-2 bg-slate-800 rounded-lg border border-white/10 focus:ring-2 focus:ring-sky-400 outline-none text-white">${escapeHtml(c.content || '')}</textarea>

          <div class="flex justify-end">
            <button type="button" class="delete-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm font-medium">❌ Hapus</button>
          </div>
        </div>
      </div>`;
  });

  // Re-attach event listener untuk tombol hapus
  document.querySelectorAll(".delete-btn").forEach((btn, i) => {
    btn.onclick = () => {
      if (confirm("Hapus template ini?")) {
        captions.splice(i, 1);
        render();
      }
    };
  });
}

function toggleExpand(id) {
  const content = document.getElementById(`${id}-content`);
  const arrow = document.getElementById(`${id}-arrow`);
  const isHidden = content.classList.contains("hidden");
  document.querySelectorAll('[id$="-content"]').forEach(e => e.classList.add("hidden"));
  document.querySelectorAll('[id$="-arrow"]').forEach(a => a.style.transform = "rotate(0deg)");
  if (isHidden) {
    content.classList.remove("hidden");
    arrow.style.transform = "rotate(180deg)";
  }
}

function addCaption() {
  captions.push({title: "", content: ""});
  render();
}

function escapeHtml(text) {
  return text ? text.replace(/</g, "&lt;").replace(/>/g, "&gt;") : "";
}

render();
</script>
</body>
</html>