<?php

session_start();

require_once __DIR__ . '/../../includes/koneksi.php';

// Hanya izinkan penghapusan melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? '';

if (!ctype_digit((string) $id) || (int) $id < 1) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID member tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

try {

    $pdo->beginTransaction();

    // Ambil data member
    $stmtMember = $pdo->prepare(
        "SELECT cabor
         FROM anggota
         WHERE id = :id
         FOR UPDATE"
    );

    $stmtMember->execute([
        'id' => (int) $id
    ]);

    $anggota = $stmtMember->fetch(PDO::FETCH_ASSOC);

    if (!$anggota) {

        $pdo->rollBack();

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data member tidak ditemukan.'
        ];

        header('Location: list.php');
        exit;
    }

    // Kembalikan kuota kelas sebelumnya
    if (!empty($anggota['cabor'])) {

        $stmtKuota = $pdo->prepare(
            "UPDATE jadwal_kelas
             SET stok = stok + 1
             WHERE judul = :cabor"
        );

        $stmtKuota->execute([
            'cabor' => $anggota['cabor']
        ]);
    }

    // Hapus member
    $stmtHapus = $pdo->prepare(
        "DELETE FROM anggota
         WHERE id = :id"
    );

    $stmtHapus->execute([
        'id' => (int) $id
    ]);

    // Simpan transaksi
    $pdo->commit();

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data member berhasil dihapus dan kuota kelas diperbarui.'
    ];

} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menghapus data member.'
    ];
}

header('Location: list.php');
exit;