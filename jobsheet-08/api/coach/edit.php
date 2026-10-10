<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

$page_title = "Edit Jadwal Kelas";

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit((string) $id) || (int) $id < 1) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID jadwal tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

// Ambil data jadwal
$stmt = $pdo->prepare(
    "SELECT * FROM jadwal_kelas WHERE id = :id"
);

$stmt->execute([
    'id' => (int) $id
]);

$kelas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kelas) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Jadwal kelas tidak ditemukan.'
    ];

    header('Location: list.php');
    exit;
}

$error = '';

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = trim($_POST['judul'] ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $tahun = $_POST['tahun'] ?? '';
    $isbn = trim($_POST['isbn'] ?? '');
    $stok = $_POST['stok'] ?? '';

    if ($judul === '') {
        $error = 'Nama kelas wajib diisi.';

    } elseif ($pengarang === '') {
        $error = 'Nama coach atau pelatih wajib diisi.';

    } elseif (
        $tahun === '' ||
        !ctype_digit((string) $tahun) ||
        (int) $tahun < 0 ||
        (int) $tahun > 23
    ) {
        $error = 'Jam latihan harus antara 0 sampai 23.';

    } elseif (
        $stok === '' ||
        !ctype_digit((string) $stok)
    ) {
        $error = 'Kuota harus berupa bilangan bulat nonnegatif.';

    } else {

        try {

            $updateStmt = $pdo->prepare(
                "UPDATE jadwal_kelas
                 SET judul = :judul,
                     pengarang = :pengarang,
                     kategori = :kategori,
                     tahun = :tahun,
                     isbn = :isbn,
                     stok = :stok
                 WHERE id = :id"
            );

            $updateStmt->execute([
                'judul' => $judul,
                'pengarang' => $pengarang,
                'kategori' => $kategori,
                'tahun' => (int) $tahun,
                'isbn' => $isbn !== '' ? $isbn : null,
                'stok' => (int) $stok,
                'id' => (int) $id
            ]);

            $_SESSION['flash'] = [
                'type' => 'success',
                'pesan' => 'Jadwal kelas berhasil diperbarui!'
            ];

            header('Location: list.php');
            exit;

        } catch (PDOException $e) {
            $error = 'Gagal memperbarui jadwal kelas.';
        }
    }

    // Tampilkan kembali nilai input jika validasi gagal
    $kelas['judul'] = $judul;
    $kelas['pengarang'] = $pengarang;
    $kelas['kategori'] = $kategori;
    $kelas['tahun'] = $tahun;
    $kelas['isbn'] = $isbn;
    $kelas['stok'] = $stok;
}

include __DIR__ . '/../includes/header.php';

?>

<section>

    <h2>EDIT JADWAL KELAS</h2>

    <?php if ($error !== ''): ?>
        <div class="flash flash-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form
        method="POST"
        action="edit.php?id=<?php echo (int) $id; ?>"
        style="max-width:600px; margin-top:20px;"
    >

        <div style="margin-bottom:18px;">

            <label for="judul">Nama Kelas / Cabor</label>

            <input
                type="text"
                id="judul"
                name="judul"
                value="<?php echo htmlspecialchars((string) $kelas['judul']); ?>"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >

        </div>

        <div style="margin-bottom:18px;">

            <label for="pengarang">Coach / Pelatih</label>

            <input
                type="text"
                id="pengarang"
                name="pengarang"
                value="<?php echo htmlspecialchars((string) $kelas['pengarang']); ?>"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >

        </div>

        <div style="margin-bottom:18px;">

            <label for="kategori">Hari Latihan</label>

            <select
                id="kategori"
                name="kategori"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >

                <?php
                $hariLatihan = [
                    'Senin & Rabu',
                    'Selasa & Kamis',
                    'Jumat & Sabtu',
                    'Sabtu & Minggu',
                    'Setiap Hari'
                ];
                ?>

                <?php foreach ($hariLatihan as $hari): ?>

                    <option
                        value="<?php echo htmlspecialchars($hari); ?>"
                        <?php echo $kelas['kategori'] === $hari ? 'selected' : ''; ?>
                    >
                        <?php echo htmlspecialchars($hari); ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div style="margin-bottom:18px;">

            <label for="tahun">Jam Latihan</label>

            <input
                type="number"
                id="tahun"
                name="tahun"
                value="<?php echo htmlspecialchars((string) $kelas['tahun']); ?>"
                min="0"
                max="23"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >

        </div>

        <div style="margin-bottom:18px;">

            <label for="isbn">Kode Kelas / Level</label>

            <input
                type="text"
                id="isbn"
                name="isbn"
                value="<?php echo htmlspecialchars((string) ($kelas['isbn'] ?? '')); ?>"
                style="width:100%; padding:10px; margin-top:8px;"
            >

        </div>

        <div style="margin-bottom:18px;">

            <label for="stok">Kuota Member</label>

            <input
                type="number"
                id="stok"
                name="stok"
                value="<?php echo htmlspecialchars((string) $kelas['stok']); ?>"
                min="0"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >

        </div>

        <button
            type="submit"
            style="
                background:#e50914;
                color:#fff;
                padding:10px 20px;
                border:none;
                border-radius:4px;
                font-weight:bold;
                cursor:pointer;
            "
        >
            SIMPAN PERUBAHAN
        </button>

        <a
            href="list.php"
            style="margin-left:10px; text-decoration:none;"
        >
            Batal
        </a>

    </form>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>