<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Session</title>
</head>
<body>

<h2>Isi Session</h2>

<pre><?php print_r($_SESSION); ?></pre>

</body>
</html>