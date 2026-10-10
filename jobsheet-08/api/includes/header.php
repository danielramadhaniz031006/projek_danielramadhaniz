<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menentukan base URL secara dinamis agar aman diakses dari root maupun subfolder api/
$script_path = dirname($_SERVER['PHP_SELF']);
$base = (str_contains($script_path, '/coach') || str_contains($script_path, '/member') || str_contains($script_path, '/buku')) ? '../' : '';

// Jika berada lebih dalam (misal di dalam subfolder api/member/)
if (str_contains($script_path, '/api/member') || str_contains($script_path, '/api/coach') || str_contains($script_path, '/api/buku')) {
    $base = '../';
} else if (str_contains($script_path, '/api')) {
    $base = '../';
}

$page_title = $page_title ?? 'ELRAM Training Camp';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> 
        ELRAM Training Camp<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?>
    </title>

    <!-- CSS UTAMA menggunakan dinamis base path -->
   <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body> 

    <header>
        <h1>ELRAM TRAINING CAMP</h1>

        <button
            type="button"
            id="nav-toggle-btn"
            class="nav-toggle-label"
            aria-label="Menu"
        >
            &#9776;
        </button>

        <nav>
            <ul>
                <li>
                    <a href="<?php echo $base; ?>index.php">
                        BERANDA
                    </a>
                </li>

                <li>
                    <a href="<?php echo $base; ?>api/coach/list.php">
                        JADWAL KELAS
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo $base; ?>api/member/list.php">
                        DAFTAR MEMBER
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main>