<?php  
session_start();  
  
// cek login  
if (!isset($_SESSION['panitia_logged_in'])) {  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    $input = $_POST['password'];  
    $data = json_decode(file_get_contents('pwpanitia.json'), true);  
    $savedPw = $data['password'] ?? '';  
  
    if ($input === $savedPw) {  
      $_SESSION['panitia_logged_in'] = true;  
      header("Location: panitia.php");  
      exit;  
    } else {  
      $error = "Password salah!";  
    }  
  }  
  
  // tampilkan halaman login modern  
  ?>    <!DOCTYPE html>    <html lang="id">  
  <head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Login Panitia OSIS SMANIX</title>  
    <script src="https://cdn.tailwindcss.com"></script>  
  </head>  
  <body class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black flex items-center justify-center">  
    <div class="bg-gray-900/80 p-8 rounded-2xl shadow-2xl w-80 backdrop-blur-md border border-gray-700 text-center animate-fadeIn">  
      <h1 class="text-2xl font-bold text-amber-400 mb-2">Login Panitia</h1>  
      <p class="text-sm text-gray-400 mb-6">OSIS SMA NEGERI 9 PANDEGLANG</p>  <?php if (isset($error)): ?>  
    <div class="bg-red-500/20 border border-red-500 text-red-300 text-sm p-2 rounded mb-4">  
      <?= htmlspecialchars($error) ?>  
    </div>  
  <?php endif; ?>  

  <form method="POST">  
    <input type="password" name="password" placeholder="Masukkan Password"  
      class="w-full p-3 mb-4 bg-gray-800 text-white rounded-lg border border-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500"  
      required />  
    <button type="submit"  
      class="w-full py-3 bg-amber-500 hover:bg-amber-600 rounded-lg font-semibold shadow-lg transition-all">  
      Masuk  
    </button>  
  </form>  

  <p class="text-xs text-gray-500 mt-6">Created by Sekbid 9 TIK</p>  
</div>  

<style>  
  @keyframes fadeIn {  
    from { opacity: 0; transform: translateY(20px); }  
    to { opacity: 1; transform: translateY(0); }  
  }  
  .animate-fadeIn {  
    animation: fadeIn 0.6s ease forwards;  
  }  
</style>

  </body>  
  </html>  
  <?php  
  exit;  
}  // jika sudah login → tampilkan twibbon editor
?>

<!DOCTYPE html>  <html lang="id">  
<head>  
  <meta charset="UTF-8" />  
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>  
  <title>Twibbon Panitia - OSIS SMAN 9 Pandeglang</title>  
  <script src="https://cdn.tailwindcss.com"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>  
</head>  
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white min-h-screen flex items-center justify-center">    <div class="w-full max-w-md p-6 text-center bg-gray-900/70 rounded-2xl shadow-2xl backdrop-blur-md border border-gray-700 animate-fadeIn">  
    <div class="flex justify-between items-center mb-4">  
      <h1 class="text-xl font-semibold text-amber-400">TWIBBON PANITIA</h1>  
      <a href="logout_panitia.php" class="text-sm text-gray-400 hover:text-red-400 transition">Logout</a>  
    </div>  
    <p class="text-xs text-gray-400 mb-4">OSIS SMA NEGERI 9 PANDEGLANG</p>  <div id="canvasContainer" class="relative w-full border-2 border-dashed border-gray-600 rounded-xl overflow-hidden bg-gray-800 mb-4">  
  <canvas id="canvas" class="w-full h-auto block"></canvas>  
  <span id="placeholderText" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-gray-500 text-sm">Upload foto dulu ya!</span>  
</div>  

<input type="file" id="upload" accept="image/*" class="hidden"/>  
<button id="uploadBtn" class="w-full py-3 rounded-xl font-semibold bg-amber-500 hover:bg-amber-600 transition-all mb-3 shadow-md">📷 Pilih Foto</button>  

<input type="range" id="zoomSlider" min="0.5" max="3" step="0.01" value="1" class="w-full accent-amber-400 mb-4 hidden"/>  

<div>  
  <p class="font-semibold text-gray-300 mb-2">Pilih Frame:</p>  
  <div id="framesContainer" class="flex justify-center flex-wrap gap-3"></div>  
</div>  

<button id="downloadBtn" disabled class="w-full py-3 mt-4 rounded-xl font-semibold bg-amber-500 hover:bg-amber-600 transition-all disabled:bg-gray-600 disabled:cursor-not-allowed shadow-md">⬇️ Download Hasil</button>  

<p class="text-xs text-gray-500 mt-6">Created & Managed by Sekbid 9 TIK</p>

  </div>    <style>  
    @keyframes fadeIn {  
      from {opacity: 0; transform: translateY(15px);}  
      to {opacity: 1; transform: translateY(0);}  
    }  
    .animate-fadeIn { animation: fadeIn 0.6s ease forwards; }  
    .frame-item img {  
      width: 64px; height: 64px; border-radius: 8px;  
      border: 2px solid #444; cursor: pointer; object-fit: cover;  
      transition: transform 0.2s, border-color 0.2s;  
    }  
    .frame-item img:hover { transform: scale(1.05); border-color: #ffb74d; }  
    .frame-item img.selected { border-color: #ff9800; box-shadow: 0 0 10px #ff980055; }  
  </style>    <script>  
    const uploadBtn = document.getElementById("uploadBtn");  
    const uploadInput = document.getElementById("upload");  
    const canvas = document.getElementById("canvas");  
    const ctx = canvas.getContext("2d");  
    const container = document.getElementById("canvasContainer");  
    const placeholderText = document.getElementById("placeholderText");  
    const downloadBtn = document.getElementById("downloadBtn");  
    const zoomSlider = document.getElementById("zoomSlider");  
    const framesContainer = document.getElementById("framesContainer");  
  
    let photo = null, frame = null, frameSelected = null;  
    let scale = 1, posX = 0, posY = 0;  
    let frameWidth = 2000, frameHeight = 2000;  
  
    uploadBtn.addEventListener("click", () => uploadInput.click());  
    uploadInput.addEventListener("change", (e) => {  
      const file = e.target.files[0];  
      if (!file) return;  
      const reader = new FileReader();  
      reader.onload = function(evt) {  
        const img = new Image();  
        img.onload = function() {  
          photo = img;  
          scale = 1;  
          posX = 0;  
          posY = 0;  
          zoomSlider.value = 1;  
          zoomSlider.classList.remove("hidden");  
          placeholderText.style.display = "none";  
          render();  
          downloadBtn.disabled = !frameSelected;  
        };  
        img.src = evt.target.result;  
      };  
      reader.readAsDataURL(file);  
    });  
  
    async function loadFrames() {  
      try {  
        const res = await fetch("frames.json");  
        const data = await res.json();  
        const panitiaFrames = data.filter(f => f.kategori === "panitia");  
        framesContainer.innerHTML = "";  
  
        panitiaFrames.forEach(f => {  
          const div = document.createElement("div");  
          div.classList.add("frame-item");  
          div.innerHTML = `<img src="${f.src}" alt="${f.nama_tampil}" title="${f.nama_tampil}" />`;  
          const img = div.querySelector("img");  
          img.addEventListener("click", () => {  
            document.querySelectorAll(".frame-item img").forEach(i => i.classList.remove("selected"));  
            img.classList.add("selected");  
            frameSelected = f.src;  
  
            const fr = new Image();  
            fr.onload = () => {  
              frame = fr;  
              frameWidth = fr.width;  
              frameHeight = fr.height;  
              render();  
              downloadBtn.disabled = !photo;  
            };  
            fr.src = f.src;  
          });  
          framesContainer.appendChild(div);  
        });  
      } catch (err) {  
        console.error("Gagal load frame:", err);  
      }  
    }  
    loadFrames();  
  
    function render() {  
      if (!frame) return;  
      canvas.width = frameWidth;  
      canvas.height = frameHeight;  
      ctx.clearRect(0, 0, canvas.width, canvas.height);  
  
      if (photo) {  
        const ratio = Math.max(canvas.width / photo.width, canvas.height / photo.height);  
        const newW = photo.width * ratio * scale;  
        const newH = photo.height * ratio * scale;  
        const x = (canvas.width - newW) / 2 + posX;  
        const y = (canvas.height - newH) / 2 + posY;  
        ctx.drawImage(photo, x, y, newW, newH);  
      }  
  
      ctx.drawImage(frame, 0, 0, canvas.width, canvas.height);  
    }  
  
    const hammer = new Hammer(container);  
    hammer.get('pinch').set({ enable: true });  
    hammer.get('pan').set({ direction: Hammer.DIRECTION_ALL });  
  
    let lastScale = 1, lastPosX = 0, lastPosY = 0;  
  
    hammer.on("pinchstart", () => lastScale = scale);  
    hammer.on("pinch", ev => {  
      scale = lastScale * ev.scale;  
      zoomSlider.value = scale;  
      render();  
    });  
  
    hammer.on("panstart", () => {  
      lastPosX = posX;  
      lastPosY = posY;  
    });  
  
    hammer.on("pan", ev => {  
      posX = lastPosX + ev.deltaX;  
      posY = lastPosY + ev.deltaY;  
      render();  
    });  
  
    zoomSlider.addEventListener("input", () => {  
      scale = parseFloat(zoomSlider.value);  
      render();  
    });  
  
    downloadBtn.addEventListener("click", () => {  
      render();  
      const link = document.createElement("a");  
      link.download = "twibbon_smanix_panitia.png";  
      link.href = canvas.toDataURL("image/png");  
      link.click();  
    });  
  </script>  
</body>  
</html>

