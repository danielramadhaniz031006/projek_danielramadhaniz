<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

/* =========================================================
   1. PROSES SIMPAN MEMBER
   ========================================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama       = trim($_POST['nama'] ?? '');
    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $cabor      = trim($_POST['cabor'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    // Validasi input
    if ($nama === '' || $no_anggota === '' || $cabor === '') {

        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => 'Nama, nomor anggota, dan pilihan kelas wajib diisi.'
        ];

        header('Location: tambah.php');
        exit;
    }

    try {

        // Mulai transaksi database
        $pdo->beginTransaction();

        // Kurangi kuota kelas yang dipilih jika masih tersedia
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

        // Periksa ketersediaan kelas
        if (!$kelas) {

            $pdo->rollBack();

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
                'type'  => 'error',
                'pesan' => $pesan
            ];

            header('Location: tambah.php');
            exit;
        }

        // Simpan data member
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

        // Simpan perubahan kuota dan data member
        $pdo->commit();

        $_SESSION['flash'] = [
            'type'  => 'success',
            'pesan' => 'Member berhasil ditambahkan dan kuota kelas berhasil diperbarui.'
        ];

        header('Location: list.php');
        exit;

    } catch (PDOException $e) {

        // Batalkan perubahan jika terjadi kegagalan
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        if ($e->getCode() === '23505') {
            $pesan = 'Nomor anggota sudah digunakan. Gunakan nomor lain.';
        } else {
            $pesan = 'Data member gagal disimpan. Periksa kembali data dan database.';
        }

        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => $pesan
        ];

        header('Location: tambah.php');
        exit;
    }
}

/* =========================================================
   2. GENERATE NOMOR KARTU MEMBER OTOMATIS
   ========================================================= */

$stmt = $pdo->query(
    "SELECT no_anggota
     FROM anggota
     ORDER BY id DESC
     LIMIT 1"
);

$last_member = $stmt->fetch(PDO::FETCH_ASSOC);

$next_no_anggota = 'A1';

if ($last_member && !empty($last_member['no_anggota'])) {

    $last_no = $last_member['no_anggota'];

    // Ambil angka dari nomor anggota terakhir
    preg_match('/\d+/', $last_no, $matches);

    if (!empty($matches[0])) {

        $next_number = (int) $matches[0] + 1;

        // Ambil awalan huruf
        $prefix = preg_replace('/\d+/', '', $last_no);

        if ($prefix === '') {
            $prefix = 'A';
        }

        $next_no_anggota = $prefix . $next_number;
    }
}

/* =========================================================
   3. AMBIL DAFTAR KELAS DAN SISA KUOTA
   ========================================================= */

$stmtPilihan = $pdo->query(
    "SELECT judul, stok
     FROM buku
     ORDER BY judul ASC"
);

$pilihanKelas = $stmtPilihan->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   4. HEADER HALAMAN
   ========================================================= */

$page_title = "Tambah Member";

include __DIR__ . '/../includes/header.php';

?>

<!-- =========================================================
     FORM TAMBAH MEMBER
     ========================================================= -->

<section style="max-width: 600px; margin: 0 auto; padding: 20px;">

    <div style="border-left: 4px solid #e50914; padding-left: 12px; margin-bottom: 25px;">

        <h2 style="margin: 0; font-size: 1.8rem; font-weight: 800; text-transform: uppercase; color: #fff;">
            TAMBAH MEMBER
        </h2>

    </div>

    <!-- Pesan notifikasi -->
    <?php if (isset($_SESSION['flash'])): ?>

        <div class="flash <?php echo $_SESSION['flash']['type'] === 'success' ? 'flash-success' : 'flash-error'; ?>">

            <?php
            echo htmlspecialchars($_SESSION['flash']['pesan']);
            ?>

        </div>

        <?php unset($_SESSION['flash']); ?>

    <?php endif; ?>

    <form
        method="POST"
        action="tambah.php"
        style="background: #181818; padding: 25px; border-radius: 8px; border: 1px solid #282828;"
    >

        <!-- NAMA MEMBER -->
        <div style="margin-bottom: 20px;">

            <label
                for="nama"
                style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;"
            >
                NAMA MEMBER
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                placeholder="Contoh: Daniel Ramadhani Zulkarnain"
                required
                style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; box-sizing: border-box;"
            >

        </div>

        <!-- ID MEMBER -->
        <div style="margin-bottom: 20px;">

            <label
                for="no_anggota"
                style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;"
            >
                ID MEMBER / NO. KARTU
            </label>

            <input
                type="text"
                id="no_anggota"
                name="no_anggota"
                value="<?php echo htmlspecialchars($next_no_anggota); ?>"
                required
                style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; font-weight: bold; box-sizing: border-box;"
            >

            <small style="color: #aaa; font-size: 0.8rem; margin-top: 5px; display: block;">
                *Nomor kartu direkomendasikan otomatis dan tetap bisa diedit.
            </small>

        </div>

        <!-- PILIHAN KELAS -->
        <div style="margin-bottom: 20px;">

            <label
                for="cabor"
                style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;"
            >
                CABOR / PILIHAN KELAS
            </label>

            <select
                id="cabor"
                name="cabor"
                required
                style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; box-sizing: border-box;"
            >

                <option value="">-- Pilih Kelas --</option>

                <?php foreach ($pilihanKelas as $kelas): ?>

                    <option
                        value="<?php echo htmlspecialchars($kelas['judul']); ?>"
                        <?php echo ((int) $kelas['stok'] <= 0) ? 'disabled' : ''; ?>
                    >

                        <?php echo htmlspecialchars($kelas['judul']); ?>

                        (Sisa kuota: <?php echo (int) $kelas['stok']; ?>)

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <!-- NOMOR HP -->
        <div style="margin-bottom: 20px;">

            <label
                for="no_hp"
                style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;"
            >
                NO. HP / WHATSAPP
            </label>

            <input
                type="text"
                id="no_hp"
                name="no_hp"
                placeholder="Contoh: 08123456789"
                style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; box-sizing: border-box;"
            >

        </div>

        <!-- TOMBOL AKSI -->
        <div style="margin-top: 25px;">

            <button
                type="submit"
                style="background: #e50914; color: #fff; padding: 12px 30px; border: none; border-radius: 4px; font-weight: bold; text-transform: uppercase; cursor: pointer; font-size: 1rem;"
            >
                SIMPAN
            </button>

            <a
                href="list.php"
                style="color: #aaa; margin-left: 15px; text-decoration: none; font-size: 0.9rem;"
            >
                Batal
            </a>

        </div>

    </form>

</section>

<?php
  
include __DIR__ . '/../includes/footer.php';

?>