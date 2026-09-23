<?php
$host = "aws-0-ap-northeast-2.pooler.supabase.com";
$port = "6543";
$db   = "postgres";
$user = "postgres.rcrycalkhcjnfnfmkxuv";
$pass = "ramadanbanda031006";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
