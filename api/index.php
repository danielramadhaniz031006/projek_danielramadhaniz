<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . '/..' . $uri;

// 1. Jika path berupa folder/direktori
if (is_dir($filePath)) {
    if (file_exists($filePath . '/index.php')) {
        require $filePath . '/index.php';
        exit;
    } elseif (file_exists($filePath . '/index.html')) {
        readfile($filePath . '/index.html');
        exit;
    }
}

// 2. Jika path berupa file langsung
if (file_exists($filePath) && !is_dir($filePath)) {
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);

    // Jika file PHP, jalankan
    if ($extension === 'php') {
        require $filePath;
        exit;
    } 
    // Jika file statis (CSS, JS, Gambar, dll), tentukan Content-Type lalu tampilkan
    else {
        $mimeTypes = [
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'svg'  => 'image/svg+xml'
        ];

        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }

        readfile($filePath);
        exit;
    }
}

// 3. Jika file tidak ditemukan
http_response_code(404);
echo "404 - Halaman tidak ditemukan";