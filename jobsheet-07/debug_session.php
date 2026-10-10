<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"><title>Debug Session | SIMPUS-Mini</title><link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header"><h1>SIMPUS-Mini</h1><a href="index.php" style="color:white">Kembali ke Beranda</a></header>
<main><section>

<h2>Isi Session</h2>

<pre class="debug-panel"><?php print_r($_SESSION); ?></pre>

</section></main><footer>&copy; 2026 SIMPUS-Mini &mdash; Jobsheet 7</footer></body>
</html> 