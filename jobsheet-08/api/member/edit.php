<?php

session_start();

require_once __DIR__ . '/../../includes/koneksi.php';

$page_title = "Edit Member";

// Periksa ID
$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id) || (int) $id < 1) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID member tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

// Ambil data member
$stmt = $pdo->prepare(
    "SELECT *
     FROM anggota
     WHERE id = :id"
);

$stmt->execute([
    'id' => (int) $id
]);

$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data member tidak ditemukan.'
    ];

    header('Location: list.php');
    exit;
}

// Ambil daftar kelas
$stmtKelas = $pdo->query(
    "SELECT judul, stok
     FROM jadwal_kelas
     ORDER BY judul ASC"
);

$pilihanKelas = $stmtKelas->fetchAll(PDO::FETCH_ASSOC);

$error = '';

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $cabor = trim($_POST['cabor'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');

    if ($no_anggota === '') {

        $error = 'Nomor anggota wajib diisi.';

    } elseif ($nama === '') {

        $error = 'Nama member wajib diisi.';

    } elseif ($cabor === '') {

        $error = 'Silakan pilih kelas.';

    } else {

        try {

            $pdo->beginTransaction();

            // Periksa apakah member pindah kelas
            if ($cabor !== $anggota['cabor']) {

                // Kurangi kuota kelas baru
                $stmtKelasBaru = $pdo->prepare(
                    "UPDATE jadwal_kelas
                     SET stok = stok - 1
                     WHERE judul = :cabor
                       AND stok > 0
                     RETURNING id"
                );

                $stmtKelasBaru->execute([
                    'cabor' => $cabor
                ]);

                $kelasBaru = $stmtKelasBaru->fetch(PDO::FETCH_ASSOC);

                if (!$kelasBaru) {

                    $pdo->rollBack();

                    $stmtCek = $pdo->prepare(
                        "SELECT id
                         FROM jadwal_kelas
                         WHERE judul = :cabor
                         LIMIT 1"
                    );

                    $stmtCek->execute([
                        'cabor' => $cabor
                    ]);

                    if (!$stmtCek->fetch(PDO::FETCH_ASSOC)) {
                        $error = 'Kelas yang dipilih tidak ditemukan.';
                    } else {
                        $error = 'Kuota kelas baru sudah habis.';
                    }

                } else {

                    // Kembalikan kuota kelas lama
                    $stmtKelasLama = $pdo->prepare(
                        "UPDATE jadwal_kelas
                         SET stok = stok + 1
                         WHERE judul = :cabor"
                    );

                    $stmtKelasLama->execute([
                        'cabor' => $anggota['cabor']
                    ]);

                }
            }

            // Simpan jika belum terjadi kegagalan pemindahan kelas
            if ($error === '') {

                $updateStmt = $pdo->prepare(
                    "UPDATE anggota
                     SET no_anggota = :no_anggota,
                         nama = :nama,
                         cabor = :cabor,
                         no_hp = :no_hp
                     WHERE id = :id"
                );

                $updateStmt->execute([
                    'no_anggota' => $no_anggota,
                    'nama'       => $nama,
                    'cabor'      => $cabor,
                    'no_hp'      => $no_hp,
                    'id'         => (int) $id
                ]);

                $pdo->commit();

                $_SESSION['flash'] = [
                    'type' => 'success',
                    'pesan' => 'Data member berhasil diperbarui!'
                ];

                header('Location: list.php');
                exit;
            }

        } catch (PDOException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if ($e->getCode() === '23505') {
                $error = 'Nomor anggota sudah digunakan. Gunakan nomor lain.';
            } else {
                $error = 'Gagal memperbarui data member.';
            }
        }
    }

    // Pertahankan nilai input jika terjadi kesalahan
    $anggota['no_anggota'] = $no_anggota;
    $anggota['nama'] = $nama;
    $anggota['cabor'] = $cabor;
    $anggota['no_hp'] = $no_hp;
}

// Header dipanggil setelah proses redirect selesai
include __DIR__ . '/../../includes/header.php';

?>

<section style="max-width:600px; margin:0 auto; padding:20px;">

    <h2>EDIT DATA MEMBER</h2>

    <?php if ($error !== ''): ?>

        <div class="flash flash-error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>

    <form
        method="POST"
        action="edit.php?id=<?php echo (int) $id; ?>"
        style="margin-top:20px;"
    >

        <!-- ID MEMBER -->
        <div style="margin-bottom:18px;">

            <label for="no_anggota">
                ID Member / No. Anggota
            </label>

            <input
                type="text"
                id="no_anggota"
                name="no_anggota"
                value="<?php echo htmlspecialchars($anggota['no_anggota']); ?>"
                required
                style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    box-sizing:border-box;
                "
            >

        </div>

        <!-- NAMA -->
        <div style="margin-bottom:18px;">

            <label for="nama">
                Nama Member
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                value="<?php echo htmlspecialchars($anggota['nama']); ?>"
                required
                style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    box-sizing:border-box;
                "
            >

        </div>

        <!-- PILIHAN KELAS -->
        <div style="margin-bottom:18px;">

            <label for="cabor">
                Cabor / Pilihan Kelas
            </label>

            <select
                id="cabor"
                name="cabor"
                required
                style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    box-sizing:border-box;
                "
            >

                <option value="">
                    -- Pilih Kelas --
                </option>

                <?php
                $kelasSaatIniAda = false;
                ?>

                <?php foreach ($pilihanKelas as $kelas): ?>

                    <?php
                    if ($kelas['judul'] === $anggota['cabor']) {
                        $kelasSaatIniAda = true;
                    }
                    ?>

                    <option
                        value="<?php echo htmlspecialchars($kelas['judul']); ?>"
                        <?php echo $kelas['judul'] === $anggota['cabor'] ? 'selected' : ''; ?>
                        <?php
                        echo (
                            (int) $kelas['stok'] <= 0 &&
                            $kelas['judul'] !== $anggota['cabor']
                        ) ? 'disabled' : '';
                        ?>
                    >

                        <?php echo htmlspecialchars($kelas['judul']); ?>

                        (Sisa kuota: <?php echo (int) $kelas['stok']; ?>)

                    </option>

                <?php endforeach; ?>

                <?php if (!$kelasSaatIniAda && !empty($anggota['cabor'])): ?>

                    <option
                        value="<?php echo htmlspecialchars($anggota['cabor']); ?>"
                        selected
                    >
                        <?php echo htmlspecialchars($anggota['cabor']); ?>
                        (Kelas sebelumnya)
                    </option>

                <?php endif; ?>

            </select>

        </div>

        <!-- NOMOR HP -->
        <div style="margin-bottom:18px;">

            <label for="no_hp">
                No. HP / WhatsApp
            </label>

            <input
                type="text"
                id="no_hp"
                name="no_hp"
                value="<?php echo htmlspecialchars($anggota['no_hp'] ?? ''); ?>"
                style="
                    width:100%;
                    padding:10px;
                    margin-top:8px;
                    box-sizing:border-box;
                "
            >

        </div>

        <!-- TOMBOL -->
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
            style="color:#aaa; margin-left:10px; text-decoration:none;"
        >
            Batal
        </a>

    </form>

</section>

<?php include __DIR__ . '/../../includes/footer.php'; ?>