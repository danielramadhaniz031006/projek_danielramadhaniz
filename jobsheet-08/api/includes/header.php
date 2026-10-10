<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    <link rel="stylesheet" href="/jobsheet-08/assets/css/style.css">
    <style>
        .brand-logo-container { display: flex; align-items: center; gap: 10px; }
        .brand-icon { background: linear-gradient(135deg, #dc2626, #991b1b); color: #fff; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: 0 0 10px rgba(220, 38, 38, 0.5); }
        .system-status-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; }
        .status-dot { width: 7px; height: 7px; background-color: #10b981; border-radius: 50%; box-shadow: 0 0 8px #10b981; animation: pulse-dot 1.5s infinite; }
        @keyframes pulse-dot { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(0.8); } }
        .btn-header-action { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #ffffff !important; padding: 0.45rem 1rem !important; border-radius: 6px; font-size: 0.8rem !important; font-weight: 800 !important; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); }
    </style>
</head>
<body> 
    <header>
        <div class="brand-logo-container">
            <div class="brand-icon">🥊</div>
            <div><h1 style="margin: 0; font-size: 1.3rem;">ELRAM TRAINING CAMP</h1></div>
            <div class="system-status-badge" style="margin-left: 10px;">
                <span class="status-dot"></span> OPEN FOR TRAINING
            </div>
        </div>

        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>

        <nav>
            <ul>
                <li><a href="<?php echo $base; ?>index.php">BERANDA</a></li>
                <li><a href="<?php echo $base; ?>buku/list.php">JADWAL KELAS</a></li>
                <li><a href="<?php echo $base; ?>anggota/list.php">DAFTAR MEMBER</a></li>
                <li style="margin-left: 10px;">
                    <a href="<?php echo $base; ?>anggota/tambah.php" class="btn-header-action">+ DAFTAR SEKARANG</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>