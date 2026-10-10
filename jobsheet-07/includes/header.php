<?php
session_start();
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
$current = basename($_SERVER['SCRIPT_NAME'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMPUS-Mini<?php echo isset($page_title) ? ' | ' . htmlspecialchars($page_title) : ''; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
<header class="site-header">
  <h1><a href="<?php echo $base; ?>index.php" style="color:inherit">SIMPUS-Mini</a></h1>
  <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Buka menu" aria-expanded="false">&#9776;</button>
  <nav id="mainNav" aria-label="Navigasi utama">
    <ul>
      <li><a class="<?php echo $current === 'index.php' ? 'active' : ''; ?>" href="<?php echo $base; ?>index.php">Beranda</a></li>
      <li><a class="<?php echo $current === 'list.php' && strpos($_SERVER['SCRIPT_NAME'], '/buku/') !== false ? 'active' : ''; ?>" href="<?php echo $base; ?>buku/list.php">Daftar Buku</a></li>
      <li><a class="<?php echo $current === 'tambah.php' && strpos($_SERVER['SCRIPT_NAME'], '/buku/') !== false ? 'active' : ''; ?>" href="<?php echo $base; ?>buku/tambah.php">Tambah Buku</a></li>
      <li><a class="<?php echo $current === 'list.php' && strpos($_SERVER['SCRIPT_NAME'], '/anggota/') !== false ? 'active' : ''; ?>" href="<?php echo $base; ?>anggota/list.php">Daftar Anggota</a></li>
      <li><a class="<?php echo $current === 'tambah.php' && strpos($_SERVER['SCRIPT_NAME'], '/anggota/') !== false ? 'active' : ''; ?>" href="<?php echo $base; ?>anggota/tambah.php">Tambah Anggota</a></li>
    </ul>
  </nav>
</header>
<main>
