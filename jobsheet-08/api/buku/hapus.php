<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Jadwal kelas berhasil dihapus!'
    ];
}

header('Location: list.php');
exit;