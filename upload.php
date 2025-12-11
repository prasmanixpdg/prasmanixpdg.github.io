<?php
$targetDir = "photo/";

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0755, true); // Buat folder jika belum ada
}

if (isset($_FILES["photo"])) {
    $file = $_FILES["photo"];
    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    $allowed = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($ext, $allowed)) {
        http_response_code(400);
        echo "Format file tidak didukung.";
        exit;
    }

    $fileName = uniqid("img_") . "." . $ext;
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        echo $targetFile;
    } else {
        http_response_code(500);
        echo "Gagal upload";
    }
} else {
    http_response_code(400);
    echo "Tidak ada file dikirim";
}
?>