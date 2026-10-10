<?php
// =========================================================
// HEADER ELRAM TRAINING CAMP
// =========================================================

$page_title = $page_title ?? 'ELRAM Training Camp';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | ELRAM Training Camp</title>

    <!-- CSS UTAMA -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="main-header">
    <div class="brand">
        <a href="/index.php" class="brand-link" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
            <span class="brand-icon" style="background: var(--primary); color: #fff; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 4px;">E</span>
            <span class="brand-text" style="color: #fff;">
                <strong>ELRAM</strong> TRAINING CAMP
            </span>
        </a>
    </div>

    <nav class="main-nav">
        <ul>
            <li>
                <a href="/index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                    Beranda
                </a>
            </li>
            <li>
                <a href="/buku/list.php" class="<?php echo $current_page === 'list.php' && str_contains($_SERVER['PHP_SELF'], '/buku/') ? 'active' : ''; ?>">
                    Jadwal Kelas
                </a>
            </li>
            <li>
                <a href="/anggota/list.php" class="<?php echo $current_page === 'list.php' && str_contains($_SERVER['PHP_SELF'], '/anggota/') ? 'active' : ''; ?>">
                    Daftar Member
                </a>
            </li>
        </ul>  
    </nav>
</header>

<main>