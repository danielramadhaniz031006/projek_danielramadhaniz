<?php

// =========================================================
// HEADER ELRAM TRAINING CAMP
// =========================================================

// Judul halaman default
$page_title = $page_title ?? 'ELRAM Training Camp';

// Deteksi halaman yang sedang dibuka
$current_page = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($page_title); ?> | ELRAM Training Camp
    </title>

    <!-- CSS UTAMA -->
    <link rel="stylesheet" href="/assets/css/style.css">

</head>

<body>

<!-- =========================================================
     HEADER DAN NAVBAR
     ========================================================= -->

<header class="main-header">

    <!-- LOGO / NAMA WEBSITE -->
    <div class="brand">

        <a href="/api/index.php" class="brand-link">

            <span class="brand-icon">E</span>

            <span class="brand-text">
                <strong>ELRAM</strong>
                <small>TRAINING CAMP</small>
            </span>

        </a>

    </div>


    <!-- NAVIGASI -->
    <nav class="main-nav">

        <ul>

            <!-- BERANDA -->
            <li>
                <a
                    href="/api/index.php"
                    class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>"
                >
                    Beranda
                </a>
            </li>


            <!-- JADWAL KELAS -->
            <li>
                <a
                    href="/api/buku/list.php"
                    class="<?php echo $current_page === 'list.php' && str_contains($_SERVER['PHP_SELF'], '/buku/') ? 'active' : ''; ?>"
                >
                    Jadwal Kelas
                </a>
            </li>


            <!-- DAFTAR MEMBER -->
            <li>
                <a
                    href="/api/anggota/list.php"
                    class="<?php echo $current_page === 'list.php' && str_contains($_SERVER['PHP_SELF'], '/anggota/') ? 'active' : ''; ?>"
                >
                    Daftar Member
                </a>
            </li>


            <!-- INFORMASI ELRAM -->
            <li>
                <a
                    href="/api/informasi.php"
                    class="<?php echo $current_page === 'informasi.php' ? 'active' : ''; ?>"
                >
                    Informasi ELRAM
                </a>
            </li>

        </ul>  

    </nav>

</header>


<!-- =========================================================
     KONTEN UTAMA
     ========================================================= -->

<main>