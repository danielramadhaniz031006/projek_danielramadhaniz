<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';


/* =========================================================
   ELRAM TRAINING CAMP
   RESET DATA MEMBER MINGGUAN
   ========================================================= */


/* =========================================================
   1. HANYA MENERIMA REQUEST POST
   ========================================================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: list.php');

    exit;
}


/* =========================================================
   2. VALIDASI TOKEN KEAMANAN
   ========================================================= */

$token = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_token']) ||
    !is_string($token) ||
    !hash_equals($_SESSION['csrf_token'], $token)
) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Permintaan reset tidak valid. Silakan coba kembali.'
    ];

    header('Location: list.php');

    exit;
}


/* =========================================================
   3. RESET SELURUH DATA MEMBER
   ========================================================= */

try {

    // Mulai transaksi database
    $pdo->beginTransaction();


    // Hapus seluruh member.
    // Jadwal kelas, coach, kapasitas, dan harga tetap aman.

    $stmt = $pdo->prepare("
        DELETE FROM public.anggota
    ");

    $stmt->execute();

    $jumlahDihapus = $stmt->rowCount();


    // Simpan perubahan
    $pdo->commit();


    // Hapus token lama
    unset($_SESSION['csrf_token']);


    // Pesan hasil reset
    if ($jumlahDihapus > 0) {

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'RESET MINGGUAN BERHASIL! Sebanyak '
                . $jumlahDihapus
                . ' data member berhasil dihapus. '
                . 'Data jadwal kelas, coach, kapasitas, '
                . 'dan harga tetap tersimpan.'
        ];

    } else {

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Tidak ada data member yang perlu dihapus. '
                . 'Jadwal kelas, coach, dan harga tetap tersimpan.'
        ];

    }


} catch (PDOException $e) {

    // Batalkan transaksi jika terjadi kesalahan
    if ($pdo->inTransaction()) {

        $pdo->rollBack();

    }


    // Catat kesalahan untuk pemeriksaan server
    error_log(
        'Reset mingguan ELRAM gagal: '
        . $e->getMessage()
    );


    // Pesan untuk pengguna
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Reset mingguan gagal. Data member belum berhasil dihapus.'
    ];

}


/* =========================================================
   4. KEMBALI KE DAFTAR MEMBER
   ========================================================= */

header('Location: list.php');

exit;

?>