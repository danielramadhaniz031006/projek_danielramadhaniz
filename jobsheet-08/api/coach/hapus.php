<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

// Penghapusan hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? '';

if (!ctype_digit((string) $id) || (int) $id < 1) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID jadwal tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

try {

    $stmt = $pdo->prepare(
        "DELETE FROM jadwal_kelas WHERE id = :id"
    );

    $stmt->execute([
        'id' => (int) $id
    ]);

    if ($stmt->rowCount() > 0) {

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Jadwal kelas berhasil dihapus!'
        ];

    } else {

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Jadwal kelas tidak ditemukan.'
        ];
    }

} catch (PDOException $e) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menghapus jadwal kelas.'
    ];
}

header('Location: list.php');
exit;