<?php

session_start();

require __DIR__ . '/../includes/koneksi.php';


/* =========================================================
   AMBIL DATA FORM
   ========================================================= */

$nama = trim($_POST['nama'] ?? '');

$noAnggota = trim($_POST['no_anggota'] ?? '');

$alamat = trim($_POST['alamat'] ?? '');

$noHp = trim($_POST['no_hp'] ?? '');


/* =========================================================
   VALIDASI
   ========================================================= */

$errors = [];


if ($nama === '') {

    $errors[] = "Nama wajib diisi.";

}


if ($noAnggota === '') {

    $errors[] = "No. Anggota wajib diisi.";

}


/* =========================================================
   JIKA VALIDASI GAGAL
   ========================================================= */

if (!empty($errors)) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');

    exit;
}


/* =========================================================
   SIMPAN DATA KE DATABASE
   ========================================================= */

$stmt = $pdo->prepare(
    "INSERT INTO anggota
        (nama, no_anggota, alamat, no_hp)
     VALUES
        (:nama, :no_anggota, :alamat, :no_hp)
     RETURNING id"
);


/* =========================================================
   TANGANI ERROR UNIQUE
   ========================================================= */

try {

    $stmt->execute([
        'nama' => $nama,
        'no_anggota' => $noAnggota,
        'alamat' => $alamat,
        'no_hp' => $noHp,
    ]);


    /* =====================================================
       BERHASIL
       ===================================================== */

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Anggota berhasil ditambahkan.'
    ];

    header('Location: list.php');

    exit;


} catch (PDOException $e) {

    /*
     * Jika No. Anggota sudah ada,
     * PostgreSQL akan menghasilkan error UNIQUE.
     */

    if ($e->getCode() === '23505') {

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.'
        ];

    } else {

        /*
         * Untuk error database lainnya,
         * jangan tampilkan error mentah ke pengguna.
         */

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data anggota gagal disimpan. Silakan coba lagi.'
        ];

    }


    header('Location: tambah.php');

    exit;
}