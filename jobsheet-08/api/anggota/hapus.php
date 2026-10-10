<?php 
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data member berhasil dihapus!'
    ]; 
}

header('Location: list.php');
exit;