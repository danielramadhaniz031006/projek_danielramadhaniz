<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Menyiapkan penyimpanan jumlah percobaan login gagal
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}

// Mengambil jumlah percobaan gagal berdasarkan username
$attempts = $_SESSION['login_attempts'][$username] ?? 0;

// Batas maksimal percobaan gagal
$maxAttempts = 3;

// Jika sudah mencapai batas percobaan
if ($attempts >= $maxAttempts) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Terlalu banyak percobaan login gagal untuk username ini. Silakan coba lagi nanti.'
    ];

    header('Location: login.php');
    exit;
}

// Mencari username di database
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Memeriksa username dan password
if ($user && password_verify($password, $user['password'])) {

    // Login berhasil, reset jumlah percobaan gagal
    unset($_SESSION['login_attempts'][$username]);

    // Menyimpan data user ke session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role']; 

    // Remember Me
    if ($remember) {
        $expire = time() + (60 * 60 * 24 * 30);

        setcookie(
            'remember_user',
            $user['id'],
            [
                'expires' => $expire,
                'path' => '/',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    }

    header('Location: ../index.php');
    exit;
}

// Jika login gagal, tambah jumlah percobaan
$_SESSION['login_attempts'][$username] = $attempts + 1;

$currentAttempts = $_SESSION['login_attempts'][$username];
$remaining = $maxAttempts - $currentAttempts;

// Pesan peringatan
if ($currentAttempts >= $maxAttempts) {
    $pesan = 'Login gagal 3 kali. Silakan coba lagi nanti.';
} else {
    $pesan = 'Username atau password salah. Sisa percobaan: ' . $remaining . ' kali.';
}

$_SESSION['flash'] = [
    'type' => 'error',
    'pesan' => $pesan
];

header('Location: login.php');
exit;