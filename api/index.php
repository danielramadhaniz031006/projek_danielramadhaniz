<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . '/..' . $uri;

// 1. Jika path yang diakses adalah folder/direktori
if (is_dir($filePath)) {
    if (file_exists($filePath . '/index.php')) {
        require $filePath . '/index.php';
        exit;
    } elseif (file_exists($filePath . '/index.html')) {
        readfile($filePath . '/index.html');
        exit;
    }
}

// 2. Jika path yang diakses adalah file langsung
if (file_exists($filePath) && !is_dir($filePath)) {
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    // JIKA FILE PHP: Harus dieksekusi menggunakan require, BUKAN readfile
    if ($extension === 'php') {
        require $filePath;
        exit;
    } 
    // JIKA FILE STATIS (CSS, JS, Gambar, HTML): Boleh dibaca langsung
    else {
        readfile($filePath);
        exit;
    }
}

// 3. Jika file tidak ditemukan
http_response_code(404);
echo "404 - Halaman tidak ditemukan";