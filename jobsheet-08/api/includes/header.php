<?php

/* =========================================================
   ELRAM TRAINING CAMP
   HEADER UTAMA
   ========================================================= */

// Memulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// 1. MENENTUKAN ROOT PROJECT
// =========================================================

$script_path = str_replace(
    '\\',
    '/',
    parse_url($_SERVER['SCRIPT_NAME'] ?? '/', PHP_URL_PATH)
);

// Jika halaman berada di dalam folder api,
// ambil direktori project sebelum /api.
if (
    preg_match(
        '#^(.*?)/api(?:/|$)#',
        $script_path,
        $matches
    )
) {
    $base = rtrim($matches[1], '/');

} else {

    // Untuk halaman yang berada langsung di root project
    $base = rtrim(
        str_replace(
            '\\',
            '/',
            dirname($script_path)
        ),
        '/'
    );
}

// Normalisasi root
if ($base === '/' || $base === '.') {
    $base = '';
}


// =========================================================
// 2. INFORMASI HALAMAN
// =========================================================

$page_title = $page_title ?? 'ELRAM Training Camp';

$current_page = basename(
    $_SERVER['PHP_SELF'] ?? ''
);


// =========================================================
// 3. URL NAVIGASI
// =========================================================

$home_url = $base . '/api/index.php';

$coach_url = $base . '/api/coach/list.php';

$member_url = $base . '/api/member/list.php';


// =========================================================
// 4. URL CSS DAN JAVASCRIPT
// =========================================================

$css_url = $base . '/assets/css/style.css';

$app_js = $base . '/assets/js/app.js';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="theme-color"
        content="#0d0e12"
    >

    <title>
        ELRAM Training Camp |
        <?php echo htmlspecialchars($page_title); ?>
    </title>

    <!-- CSS UTAMA -->
    <link
        rel="stylesheet"
        href="<?php echo htmlspecialchars($css_url); ?>"
    >

</head>

<body>

    <!-- =====================================================
         HEADER DAN NAVBAR
         ===================================================== -->

    <header>

        <h1>
            ELRAM TRAINING CAMP
        </h1>

        <!-- Tombol menu mobile -->

        <button
            type="button"
            id="nav-toggle-btn"
            class="nav-toggle-label"
            aria-label="Buka atau tutup menu"
            aria-expanded="false"
            aria-controls="main-navigation"
        >
            &#9776;
        </button>

        <!-- Navigasi utama -->

        <nav id="main-navigation">

            <ul>

                <li>
                    <a
                        href="<?php echo htmlspecialchars($home_url); ?>"
                        <?php echo $current_page === 'index.php' ? 'aria-current="page"' : ''; ?>
                    >
                        BERANDA
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo htmlspecialchars($coach_url); ?>"
                        <?php echo (
                            strpos(
                                str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''),
                                '/api/coach/'
                            ) !== false
                        ) ? 'aria-current="page"' : ''; ?>
                    >
                        JADWAL KELAS
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo htmlspecialchars($member_url); ?>"
                        <?php echo (
                            strpos(
                                str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''),
                                '/api/member/'
                            ) !== false
                        ) ? 'aria-current="page"' : ''; ?>
                    >
                        DAFTAR MEMBER
                    </a>
                </li>

            </ul>

        </nav>

    </header>


    <!-- =====================================================
         KONTEN UTAMA HALAMAN
         ===================================================== -->

    <main>