<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prefix relatif ke root proyek ini supaya /assets, /index.php, dst
// tetap benar walau proyek diakses lewat subfolder.
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ELRAM Training Camp<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <header>
        <h1>ELRAM TRAINING CAMP</h1>
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
        <nav>
            <ul>
                <li><a href="<?php echo $base; ?>index.php">BERANDA</a></li>
                <li><a href="<?php echo $base; ?>buku/list.php">JADWAL KELAS</a></li>
                <li><a href="<?php echo $base; ?>buku/tambah.php">TAMBAH JADWAL KELAS</a></li>
                <li><a href="<?php echo $base; ?>anggota/list.php">DAFTAR MEMBER</a></li>
                <li><a href="<?php echo $base; ?>anggota/tambah.php">TAMBAH MEMBER</a></li>
            </ul>
        </nav>
    </header>

    <main>