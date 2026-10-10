<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

// Ambil data dari form
$judul = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$tahun = $_POST['tahun'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stok = $_POST['stok'] ?? '';

// Validasi
$errors = [];

if ($judul === '') {
    $errors[] = "Nama kelas wajib diisi.";
}

if ($pengarang === '') {
    $errors[] = "Nama coach atau pelatih wajib diisi.";
}

if (
    $tahun === '' ||
    !ctype_digit((string) $tahun) ||
    (int) $tahun < 0 ||
    (int) $tahun > 23
) {
    $errors[] = "Jam latihan harus berupa angka antara 0 sampai 23.";
}

if (
    $stok === '' ||
    !ctype_digit((string) $stok)
) {
    $errors[] = "Kuota member harus berupa bilangan bulat nonnegatif.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');
    exit;
}

try {
    // Simpan jadwal kelas
    $stmt = $pdo->prepare(
        "INSERT INTO jadwal_kelas
        (judul, pengarang, tahun, isbn, stok, kategori)
        VALUES
        (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
    );

    $stmt->execute([
        'judul' => $judul,
        'pengarang' => $pengarang,
        'tahun' => (int) $tahun,
        'isbn' => $isbn !== '' ? $isbn : null,
        'stok' => (int) $stok,
        'kategori' => $kategori
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Jadwal kelas berhasil ditambahkan!'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menambahkan jadwal kelas. Periksa tabel database.'
    ];

    header('Location: tambah.php');
    exit;
}