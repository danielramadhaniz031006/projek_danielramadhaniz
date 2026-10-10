<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';


// =========================================================
// 1. PASTIKAN REQUEST MENGGUNAKAN POST
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: list.php');

    exit;
}


// =========================================================
// 2. VALIDASI ID
// =========================================================

$id = $_POST['id'] ?? '';

if (
    !ctype_digit((string) $id)
    || (int) $id < 1
) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID member tidak valid.',
    ];

    header('Location: list.php');

    exit;
}


// =========================================================
// 3. HAPUS MEMBER
// =========================================================

try {

    $stmt = $pdo->prepare("
        DELETE FROM public.anggota
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => (int) $id,
    ]);


    // Periksa hasil penghapusan
    if ($stmt->rowCount() > 0) {

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Data member berhasil dihapus.',
        ];

    } else {

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data member tidak ditemukan.',
        ];
    }


} catch (PDOException $e) {

    error_log(
        'Gagal menghapus member: '
        . $e->getMessage()
    );

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menghapus data member.',
    ];
}


// =========================================================
// 4. KEMBALI KE DAFTAR MEMBER
// =========================================================

header('Location: list.php');

exit;