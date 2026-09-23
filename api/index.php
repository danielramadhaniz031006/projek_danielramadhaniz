<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . '/..' . $uri;

// Cek apakah path yang diminta merupakan folder/direktori
if (is_dir($filePath)) {
    // Jika ada index.php di dalam folder tersebut, jalankan (require)
    if (file_exists($filePath . '/index.php')) {
        require $filePath . '/index.php';
        exit;
    } 
    // Jika ada index.html di dalam folder tersebut, baca filenya
    elseif (file_exists($filePath . '/index.html')) {
        readfile($filePath . '/index.html');
        exit;
    }
}

// Jika path berupa file langsung yang ada di server
if (file_exists($filePath) && !is_dir($filePath)) {
    // Baris 53 kamu sebelumnya yang memicu eror saat membaca direktori
    readfile($filePath); 
    exit;
}

// Jika file/folder tidak ditemukan
http_response_code(404);
echo "404 - Halaman tidak ditemukan";