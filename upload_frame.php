<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Proses upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = $_POST['kategori'] ?? '';
    $nama_file = $_POST['nama_file'] ?? '';
    $nama_tampil = $_POST['nama_tampil'] ?? '';
    $file = $_FILES['frame_file'] ?? null;

    header('Content-Type: application/json');

    if (!$kategori || !$nama_file || !$nama_tampil || !$file) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi']);
        exit;
    }

    $allowed_ext = ['png', 'jpg', 'jpeg', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        echo json_encode(['status' => 'error', 'message' => 'Format file tidak didukung']);
        exit;
    }

    $folder = ($kategori === 'panitia') ? 'twibbons/panitia/' : 'twibbons/peserta/';
    if (!is_dir($folder)) mkdir($folder, 0755, true);

    $filename = $nama_file . '.' . $ext;
    $target_path = $folder . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $json_file = 'frames.json';
        $frames = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) ?? [] : [];
        $frames[] = [
            'nama_file' => $nama_file,
            'nama_tampil' => $nama_tampil,
            'src' => $target_path,
            'kategori' => $kategori
        ];
        file_put_contents($json_file, json_encode($frames, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'message' => 'Frame berhasil diupload', 'filename' => $filename, 'kategori' => $kategori]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal upload file']);
    }
    exit;
}

// Hapus frame
if (isset($_GET['delete'])) {
    $index = intval($_GET['delete']);
    $json_file = 'frames.json';
    if (file_exists($json_file)) {
        $frames = json_decode(file_get_contents($json_file), true) ?? [];
        if (isset($frames[$index])) {
            $f = $frames[$index];
            $file_path = $f['src'] ?? '';
            if ($file_path && file_exists($file_path)) unlink($file_path);
            array_splice($frames, $index, 1);
            file_put_contents($json_file, json_encode($frames, JSON_PRETTY_PRINT));
        }
    }
    header("Location: upload_frame.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upload Frame Twibbon</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #121212;
      color: #f0f0f0;
      margin: 0;
      padding: 20px;
    }
    .back-button {
      position: absolute;
      top: 15px;
      left: 15px;
    }
    .back-button a {
      color: #00bfff;
      text-decoration: none;
      font-size: 11px;
      font-weight: 500;
      background: #1f1f2e;
      padding: 6px 10px;
      border-radius: 4px;
      border: 1px solid #00bfff;
      transition: background 0.2s, color 0.2s;
    }
    .back-button a:hover {
      background: #00bfff;
      color: #000;
    }
    h2 {
      text-align: center;
      color: #00bfff;
      margin-bottom: 20px;
    }
    form {
      background-color: #1f1f2e;
      max-width: 500px;
      margin: auto;
      padding: 24px;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
    }
    .input-group {
      margin-bottom: 16px;
    }
    input[type="text"],
    input[type="file"],
    select {
      width: 100%;
      padding: 10px;
      background: #2c2c3c;
      border: 1px solid #444;
      border-radius: 6px;
      color: #fff;
    }
    .input-desc {
      font-size: 12px;
      color: #aaa;
      margin-top: 4px;
    }
    button {
      background: #00bfff;
      color: #000;
      font-weight: bold;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.2s;
    }
    button:hover {
      background: #00a5dd;
    }
    #preview {
      text-align: center;
      margin-top: 20px;
    }
    #preview img {
      max-width: 100%;
      max-height: 200px;
      border-radius: 10px;
      border: 1px solid #555;
    }
    #message {
      text-align: center;
      margin-top: 20px;
      font-weight: bold;
    }
    #message.success { color: #4caf50; }
    #message.error { color: #ff6b6b; }
    table {
      width: 95%;
      margin: 40px auto;
      border-collapse: separate;
      border-spacing: 0;
      background-color: #1a1a2e;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.4);
    }
    th {
      background-color: #26293b;
      color: #00bfff;
      font-weight: 600;
      padding: 14px 10px;
      font-size: 15px;
    }
    td {
      padding: 12px 10px;
      border-top: 1px solid #333;
      font-size: 14px;
    }
    tr:nth-child(even) td {
      background-color: #202233;
    }
    tr:hover td {
      background-color: #2b2e45;
    }
    img.frame-thumb {
      max-height: 60px;
      border-radius: 6px;
      border: 1px solid #444;
    }
    a.delete-btn {
      color: #ff6b6b;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }
    a.delete-btn:hover {
      color: #ff4c4c;
      text-decoration: underline;
    }
    .refresh-btn {
      display: block;
      text-align: center;
      margin: 20px auto;
    }
    .refresh-btn a {
      color: #00bfff;
      background-color: #1f1f2e;
      padding: 10px 20px;
      border: 1px solid #00bfff;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.2s;
    }
    .refresh-btn a:hover {
      background-color: #00bfff;
      color: #000;
    }
  </style>
</head>
<body>

<div class="back-button">
  <a href="index.php">⬅️ Kembali</a>
</div>

<h2>Upload Frame Twibbon</h2>

<form id="uploadForm" enctype="multipart/form-data" autocomplete="off">
  <div class="input-group">
    <label for="kategori">Kategori:</label>
    <select id="kategori" name="kategori" required>
      <option value="">-- Pilih Kategori --</option>
      <option value="panitia">Panitia</option>
      <option value="peserta">Peserta</option>
    </select>
    <div class="input-desc">Pilih kategori frame: Panitia atau Peserta.</div>
  </div>

  <div class="input-group">
    <label for="nama_file">Nama File Frame (tanpa spasi):</label>
    <input type="text" id="nama_file" name="nama_file" placeholder="Contoh: mpls_2025" pattern="^[a-z0-9_]+$" required />
    <div class="input-desc">Nama file (huruf kecil, angka, underscore).</div>
  </div>

  <div class="input-group">
    <label for="nama_tampil">Nama Tampilan Frame:</label>
    <input type="text" id="nama_tampil" name="nama_tampil" placeholder="Contoh: MPLS 2025" required />
    <div class="input-desc">Nama yang ditampilkan di pilihan frame.</div>
  </div>

  <div class="input-group">
    <label for="frame_file">Pilih File Frame:</label>
    <input type="file" id="frame_file" name="frame_file" accept="image/png,image/jpeg,image/jpg,image/gif" required />
    <div class="input-desc">Format didukung: PNG, JPG, JPEG, GIF.</div>
  </div>

  <button type="submit">Upload Frame</button>
</form>

<div id="preview"></div>
<div id="message"></div>

<?php
$json_file = 'frames.json';
$frames = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) ?? [] : [];
if (count($frames) > 0):
?>
<div class="refresh-btn">
  <a href="upload_frame.php">🔄 Refresh</a>
</div>
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Preview</th>
      <th>Nama File</th>
      <th>Nama Tampilan</th>
      <th>Kategori</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($frames as $i => $f): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><img class="frame-thumb" src="<?= htmlspecialchars($f['src']) ?>" alt="<?= htmlspecialchars($f['nama_tampil']) ?>"></td>
      <td><?= htmlspecialchars($f['nama_file']) ?></td>
      <td><?= htmlspecialchars($f['nama_tampil']) ?></td>
      <td><?= ucfirst(htmlspecialchars($f['kategori'])) ?></td>
      <td><a class="delete-btn" href="?delete=<?= $i ?>" onclick="return confirm('Yakin mau hapus frame ini?')">Hapus</a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p style="text-align:center; color: #aaa;">Belum ada frame yang diupload.</p>
<?php endif; ?>

<script>
const form = document.getElementById('uploadForm');
const preview = document.getElementById('preview');
const message = document.getElementById('message');
const fileInput = document.getElementById('frame_file');

fileInput.addEventListener('change', () => {
  const file = fileInput.files[0];
  if (!file) {
    preview.innerHTML = '';
    return;
  }
  const url = URL.createObjectURL(file);
  preview.innerHTML = `<img src="${url}" alt="Preview Frame">`;
});

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  message.textContent = '';
  message.className = '';

  const formData = new FormData(form);
  const nama_file = formData.get('nama_file');
  const nama_tampil = formData.get('nama_tampil');
  const kategori = formData.get('kategori');
  const file = fileInput.files[0];

  if (!/^[a-z0-9_]+$/.test(nama_file)) {
    message.textContent = 'Nama file hanya boleh huruf kecil, angka, dan underscore.';
    message.className = 'error';
    return;
  }

  if (!nama_tampil.trim() || !kategori || !file) {
    message.textContent = 'Semua field harus diisi.';
    message.className = 'error';
    return;
  }

  const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
  if (!allowedTypes.includes(file.type)) {
    message.textContent = 'Format file tidak didukung.';
    message.className = 'error';
    return;
  }

  form.querySelector('button[type="submit"]').disabled = true;

  try {
    const res = await fetch('', { method: 'POST', body: formData });
    const data = await res.json();

    if (data.status === 'success') {
      message.textContent = data.message;
      message.className = 'success';
      preview.innerHTML = `<img src="twibbons/${data.kategori}/${data.filename}" alt="Frame Uploaded">`;
      form.reset();
    } else {
      message.textContent = data.message;
      message.className = 'error';
    }
  } catch (err) {
    message.textContent = 'Terjadi kesalahan saat upload.';
    message.className = 'error';
  }

  form.querySelector('button[type="submit"]').disabled = false;
});
</script>

</body>
</html>