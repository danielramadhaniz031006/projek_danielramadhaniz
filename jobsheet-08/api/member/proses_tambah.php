<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';


// =========================================================
// 1. PASTIKAN REQUEST BERASAL DARI FORM POST
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: tambah.php');

    exit;
}


// =========================================================
// 2. AMBIL DATA FORM
// =========================================================

$nama = trim($_POST['nama'] ?? '');

$nomorMember = trim(
    $_POST['nomor_member']
    ?? $_POST['no_anggota']
    ?? ''
);

$jadwalKelasId = trim(
    (string) ($_POST['jadwal_kelas_id'] ?? '')
);

$level = trim(
    $_POST['level'] ?? 'Pemula (Beginner)'
);

$nomorHp = trim(
    $_POST['nomor_hp']
    ?? $_POST['no_hp']
    ?? ''
);


// =========================================================
// 3. VALIDASI INPUT
// =========================================================

$errors = [];

if ($nama === '') {
    $errors[] = 'Nama member wajib diisi.';
}

if (mb_strlen($nama) > 100) {
    $errors[] = 'Nama member maksimal 100 karakter.';
}

if ($nomorMember === '') {
    $errors[] = 'Nomor member wajib diisi.';
}

if (mb_strlen($nomorMember) > 20) {
    $errors[] = 'Nomor member maksimal 20 karakter.';
}

if (
    !ctype_digit($jadwalKelasId)
    || (int) $jadwalKelasId < 1
) {
    $errors[] = 'Silakan pilih jadwal kelas yang valid.';
}

if (mb_strlen($nomorHp) > 20) {
    $errors[] = 'Nomor HP maksimal 20 karakter.';
}


// Pilihan level yang diizinkan
$levelValid = [
    'Pemula (Beginner)',
    'Menengah (Intermediate)',
    'Mahir (Advanced)',
];

if (!in_array($level, $levelValid, true)) {
    $level = 'Pemula (Beginner)';
}


// Jika validasi gagal
if (!empty($errors)) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors),
    ];

    header('Location: tambah.php');

    exit;
}


// =========================================================
// 4. SIMPAN MEMBER DAN PERIKSA KUOTA
// =========================================================

try {

    $pdo->beginTransaction();


    // Kunci baris kelas agar pemeriksaan kuota
    // dilakukan sebelum member baru disimpan.

    $stmtKelas = $pdo->prepare("
        SELECT
            id,
            nama_kelas,
            kapasitas
        FROM public.jadwal_kelas
        WHERE id = :id
        FOR UPDATE
    ");

    $stmtKelas->execute([
        'id' => (int) $jadwalKelasId,
    ]);

    $kelas = $stmtKelas->fetch(PDO::FETCH_ASSOC);


    // Kelas tidak ditemukan
    if (!$kelas) {

        $pdo->rollBack();

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Jadwal kelas yang dipilih tidak ditemukan.',
        ];

        header('Location: tambah.php');

        exit;
    }


    // Hitung member yang terdaftar pada kelas
    $stmtJumlah = $pdo->prepare("
        SELECT COUNT(*)
        FROM public.anggota
        WHERE jadwal_kelas_id = :id
    ");

    $stmtJumlah->execute([
        'id' => (int) $jadwalKelasId,
    ]);

    $jumlahMember = (int) $stmtJumlah->fetchColumn();


    // Periksa kapasitas
    if ($jumlahMember >= (int) $kelas['kapasitas']) {

        $pdo->rollBack();

        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Kuota kelas '
                . $kelas['nama_kelas']
                . ' sudah penuh.',
        ];

        header('Location: tambah.php');

        exit;
    }


    // Simpan member
    $stmtInsert = $pdo->prepare("
        INSERT INTO public.anggota
            (
                nomor_member,
                nama,
                jadwal_kelas_id,
                level,
                nomor_hp
            )
        VALUES
            (
                :nomor_member,
                :nama,
                :jadwal_kelas_id,
                :level,
                :nomor_hp
            )
    ");

    $stmtInsert->execute([
        'nomor_member' => $nomorMember,
        'nama' => $nama,
        'jadwal_kelas_id' => (int) $jadwalKelasId,
        'level' => $level,
        'nomor_hp' => $nomorHp !== '' ? $nomorHp : null,
    ]);


    // Simpan transaksi
    $pdo->commit();


    // Pesan berhasil
    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Member berhasil ditambahkan.',
    ];

    header('Location: list.php');

    exit;


} catch (PDOException $e) {

    // Batalkan transaksi jika gagal
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }


    // Nomor member duplikat
    if ($e->getCode() === '23505') {

        $pesan = 'Nomor member sudah digunakan. Gunakan nomor lain.';

    } else {

        error_log(
            'Gagal menambahkan member: ' . $e->getMessage()
        );

        $pesan = 'Data member gagal disimpan. '
            . 'Periksa konfigurasi database.';
    }


    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => $pesan,
    ];

    header('Location: tambah.php');

    exit;
}