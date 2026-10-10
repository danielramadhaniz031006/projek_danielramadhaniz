<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menentukan lokasi root project secara dinamis
$script_path = str_replace(
    '\\',
    '/',
    $_SERVER['SCRIPT_NAME']
);

// Mencari root project jika halaman berada di dalam api/coach,
// api/member, atau folder modul lainnya.
if (
    preg_match(
        '#^(.*?)/(?:api/)?(?:coach|member|buku)(?:/|$)#',
        $script_path,
        $matches
    )
) {
    $base = $matches[1];
} else {
    $base = rtrim(
        str_replace('\\', '/', dirname($script_path)),
        '/'
    );

    if ($base === '/' || $base === '.') {
        $base = '';
    }
}

// Judul halaman
$page_title = $page_title ?? 'ELRAM Training Camp';

// Halaman yang sedang dibuka
$current_page = basename($_SERVER['PHP_SELF']);

// URL navigasi
$home_url = $base . '/index.php';
$coach_url = $base . '/api/coach/list.php';
$member_url = $base . '/api/member/list.php';

// URL aset
$css_url = $base . '/assets/css/style.css';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        ELRAM Training Camp |
        <?php echo htmlspecialchars($page_title); ?>
    </title>

    <!-- CSS utama -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars($css_url); ?>">

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
                    <a href="<?php echo htmlspecialchars($home_url); ?>">
                        BERANDA
                    </a>
                </li>

                <li>
                    <a href="<?php echo htmlspecialchars($coach_url); ?>">
                        JADWAL KELAS
                    </a>
                </li>

                <li>
                    <a href="<?php echo htmlspecialchars($member_url); ?>">
                        DAFTAR MEMBER
                    </a>
                </li>

            </ul>

        </nav>

    </header>

    <main>