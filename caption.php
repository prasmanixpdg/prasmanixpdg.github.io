<?php
$file = "caption.json";
$captions = [];

if (file_exists($file)) {
  $captions = json_decode(file_get_contents($file), true);
  if (!is_array($captions)) $captions = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Caption | OSIS SMANIX</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
<style>
  * {margin: 0; padding: 0; box-sizing: border-box;}
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #0f0f0f, #1a1a1a);
    color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    overflow: hidden;
    opacity: 0;
    animation: fadeIn 0.6s forwards;
  }
  .container {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 35px 28px;
    width: 90%;
    max-width: 430px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
  }
  h1 {
    font-size: 26px;
    font-weight: 700;
    color: #00d4ff;
    margin-bottom: 20px;
  }
  label {
    display: block;
    text-align: left;
    font-weight: 500;
    font-size: 14px;
    color: #ccc;
    margin-bottom: 6px;
  }
  select, input, textarea {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    background: rgba(255,255,255,0.08);
    color: #fff;
    margin-bottom: 14px;
    font-size: 14px;
    transition: all 0.2s ease;
  }
  select:focus, input:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 10px #00d4ff;
  }
  button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 8px;
    transition: all 0.25s ease;
  }
  .btn-main {
    background: linear-gradient(135deg, #00d4ff, #007bff);
    color: white;
  }
  .btn-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 15px rgba(0,212,255,0.6);
  }
  .btn-copy {
    background: linear-gradient(135deg, #00ff99, #00cc66);
    color: white;
  }
  .btn-copy:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 15px rgba(0,255,153,0.6);
  }
  textarea#hasil {
    resize: none;
    height: 120px;
    background: rgba(255,255,255,0.08);
    color: #00d4ff;
    font-weight: 500;
  }
  .back-link {
    display: block;
    margin-top: 20px;
    text-decoration: none;
    color: #00d4ff;
    font-size: 14px;
  }
  .back-link:hover {text-decoration: underline;}
  @keyframes fadeIn {
    from {opacity: 0; transform: translateY(15px);}
    to {opacity: 1; transform: translateY(0);}
  }
</style>
</head>
<body>
  <div class="container">
    <h1>📝 Caption</h1>

    <label for="template">Pilih Template Caption:</label>
    <select id="template">
      <?php foreach ($captions as $i => $c): ?>
        <option value="<?= $i ?>"><?= htmlspecialchars($c['title']) ?></option>
      <?php endforeach; ?>
    </select>

    <form id="dynamicForm"></form>

    <button id="buatCaption" class="btn-main">✨ Buat Caption</button>
    <textarea id="hasil" readonly placeholder="Hasil caption akan muncul di sini..."></textarea>
    <button id="salin" class="btn-copy">📋 Salin Caption</button>

    <a href="index.php" class="back-link">⬅ Kembali ke Beranda</a>
  </div>

<script>
const captions = <?= json_encode($captions, JSON_UNESCAPED_UNICODE) ?>;
const templateSelect = document.getElementById("template");
const form = document.getElementById("dynamicForm");
const hasil = document.getElementById("hasil");

function generateForm() {
  const selected = captions[templateSelect.value];
  if (!selected) return;

  const content = selected.content;
  const matches = content.match(/{(.*?)}/g) || [];
  form.innerHTML = matches.map(m => {
    const name = m.replace(/[{}]/g, "");
    return `
      <div>
        <label>${name.charAt(0).toUpperCase() + name.slice(1)}:</label>
        <input type="text" id="${name}" placeholder="Masukkan ${name}">
      </div>`;
  }).join("");
}

function buatCaption() {
  const selected = captions[templateSelect.value];
  if (!selected) return;

  let result = selected.content;
  const inputs = form.querySelectorAll("input");
  inputs.forEach(input => {
    result = result.replaceAll(`{${input.id}}`, input.value || "");
  });
  hasil.value = result;
}

function salinCaption() {
  hasil.select();
  document.execCommand("copy");
  alert("✅ Caption berhasil disalin!");
}

templateSelect.addEventListener("change", generateForm);
document.getElementById("buatCaption").addEventListener("click", buatCaption);
document.getElementById("salin").addEventListener("click", salinCaption);

generateForm(); // render awal
</script>
</body>
</html>