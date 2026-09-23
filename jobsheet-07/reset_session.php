<?php
session_start();

session_destroy();

header('Location: buku/list.php');
exit;