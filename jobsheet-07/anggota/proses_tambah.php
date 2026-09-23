<?php
session_start();

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];


/*
|--------------------------------------------------------------------------
| VALIDASI NAMA
|--------------------------------------------------------------------------
*/

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}


/*
|--------------------------------------------------------------------------
| VALIDASI NO. ANGGOTA
|--------------------------------------------------------------------------
*/

if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}


/*
|--------------------------------------------------------------------------
| VALIDASI NO. HP
|--------------------------------------------------------------------------
| No. HP boleh dikosongkan.
| Jika diisi, hanya boleh mengandung angka.
*/

if ($noHp !== '' && !preg_match('/^[0-9]+$/', $noHp)) {
    $errors[] = "No. HP hanya boleh berisi angka.";
}


/*
|--------------------------------------------------------------------------
| SIAPKAN SESSION ANGGOTA
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}


/*
|--------------------------------------------------------------------------
| CEK NO. ANGGOTA DUPLIKAT
|--------------------------------------------------------------------------
*/

foreach ($_SESSION['anggota'] as $anggota) {
    if (
        isset($anggota['no_anggota']) &&
        $anggota['no_anggota'] === $noAnggota
    ) {
        $errors[] = "No. Anggota sudah digunakan.";
        break;
    }
}


/*
|--------------------------------------------------------------------------
| JIKA ADA ERROR
|--------------------------------------------------------------------------
*/

if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| SIMPAN DATA ANGGOTA
|--------------------------------------------------------------------------
*/

$_SESSION['anggota'][] = [
    'nama' => $nama,
    'no_anggota' => $noAnggota,
    'alamat' => $alamat,
    'no_hp' => $noHp,
];


/*
|--------------------------------------------------------------------------
| FLASH MESSAGE BERHASIL
|--------------------------------------------------------------------------
*/

$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Anggota berhasil ditambahkan.'
];

header('Location: list.php');
exit;