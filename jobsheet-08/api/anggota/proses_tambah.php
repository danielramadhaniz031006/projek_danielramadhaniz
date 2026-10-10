<?php

session_start();

require __DIR__ . '/../includes/koneksi.php';

// Ambil data dari form
$nama       = trim($_POST['nama'] ?? '');
$no_anggota = trim($_POST['no_anggota'] ?? '');
$cabor      = trim($_POST['cabor'] ?? '');
$no_hp      = trim($_POST['no_hp'] ?? '');

// Validasi
$errors = [];

if ($nama === '') {
    $errors[] = 'Nama member wajib diisi.';
}

if ($no_anggota === '') {
    $errors[] = 'Nomor anggota wajib diisi.';
}

if ($cabor === '') {
    $errors[] = 'Silakan pilih kelas.';
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
    // Mulai transaksi agar penyimpanan member dan pengurangan kuota
    // berhasil bersama-sama atau dibatalkan bersama-sama.
    $pdo->beginTransaction();

    // Periksa kelas dan kurangi kuota jika masih tersedia.
    $stmtKelas = $pdo->prepare(
        "UPDATE buku
         SET stok = stok - 1
         WHERE judul = :cabor
           AND stok > 0
         RETURNING id"
    );

    $stmtKelas->execute([
        'cabor' => $cabor
    ]);

    $kelas = $stmtKelas->fetch(PDO::FETCH_ASSOC);

    if (!$kelas) {
        $pdo->rollBack();

        // Periksa apakah kelas memang ada.
        $cekKelas = $pdo->prepare(
            "SELECT id FROM buku WHERE judul = :cabor"
        );

        $cekKelas->execute([
            'cabor' => $cabor
        ]);

        if (!$cekKelas->fetch(PDO::FETCH_ASSOC)) {
            $pesan = 'Kelas yang dipilih tidak ditemukan.';
        } else {
            $pesan = 'Kuota kelas tersebut sudah habis.';
        }

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => $pesan
        ];

        header('Location: tambah.php');
        exit;
    }

    // Simpan data member, termasuk kelas yang dipilih.
    $stmtMember = $pdo->prepare(
        "INSERT INTO anggota
            (nama, no_anggota, cabor, no_hp)
         VALUES
            (:nama, :no_anggota, :cabor, :no_hp)"
    );

    $stmtMember->execute([
        'nama'       => $nama,
        'no_anggota' => $no_anggota,
        'cabor'      => $cabor,
        'no_hp'      => $no_hp
    ]);

    // Simpan transaksi.
    $pdo->commit();

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Member berhasil ditambahkan dan kuota kelas berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {

    // Batalkan perubahan jika penyimpanan gagal.
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if ($e->getCode() === '23505') {
        $pesan = 'Nomor anggota sudah digunakan. Silakan gunakan nomor lain.';
    } else {
        $pesan = 'Data member gagal disimpan. Silakan periksa kembali.';
    }

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => $pesan
    ];

    header('Location: tambah.php');
    exit;
}