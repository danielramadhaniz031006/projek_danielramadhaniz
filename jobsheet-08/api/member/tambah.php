<?php

session_start();

require_once __DIR__ . '/../../includes/koneksi.php';

$page_title = "Tambah Member";

// Ambil rekomendasi nomor member terakhir
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

    if (preg_match('/^(.*?)(\d+)$/', $last_no, $matches)) {

        $prefix = $matches[1];
        $last_number = (int) $matches[2];

        $next_no_anggota = $prefix . ($last_number + 1);

    } else {
        $next_no_anggota = 'A1';
    }
}

// Ambil pilihan kelas dari tabel jadwal_kelas
$stmtPilihan = $pdo->query(
    "SELECT judul, stok
     FROM jadwal_kelas
     ORDER BY judul ASC"
);

$pilihanKelas = $stmtPilihan->fetchAll(PDO::FETCH_ASSOC);

// Ambil notifikasi
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Tampilkan header setelah proses PHP selesai
include __DIR__ . '/../../includes/header.php';

?>

<section style="max-width:600px; margin:0 auto; padding:20px;">

    <div style="border-left:4px solid #e50914; padding-left:12px; margin-bottom:25px;">

        <h2 style="
            margin:0;
            font-size:1.8rem;
            font-weight:800;
            text-transform:uppercase;
            color:#fff;
        ">
            TAMBAH MEMBER
        </h2>

    </div>

    <!-- NOTIFIKASI -->
    <?php if ($flash): ?>

        <div class="flash <?php echo $flash['type'] === 'success' ? 'flash-success' : 'flash-error'; ?>">

            <?php echo htmlspecialchars($flash['pesan']); ?>

        </div>

    <?php endif; ?>

    <!-- FORM TAMBAH MEMBER -->
    <form
        method="POST"
        action="proses_tambah.php"
        style="
            background:#181818;
            padding:25px;
            border-radius:8px;
            border:1px solid #282828;
        "
    >

        <!-- NAMA MEMBER -->
        <div style="margin-bottom:20px;">

            <label
                for="nama"
                style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                    font-size:0.9rem;
                    color:#fff;
                "
            >
                NAMA MEMBER
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                placeholder="Masukkan nama member"
                required
                style="
                    width:100%;
                    padding:12px;
                    border-radius:4px;
                    border:1px solid #333;
                    background:#222;
                    color:#fff;
                    box-sizing:border-box;
                "
            >

        </div>

        <!-- ID MEMBER -->
        <div style="margin-bottom:20px;">

            <label
                for="no_anggota"
                style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                    font-size:0.9rem;
                    color:#fff;
                "
            >
                ID MEMBER / NO. KARTU
            </label>

            <input
                type="text"
                id="no_anggota"
                name="no_anggota"
                value="<?php echo htmlspecialchars($next_no_anggota); ?>"
                required
                style="
                    width:100%;
                    padding:12px;
                    border-radius:4px;
                    border:1px solid #333;
                    background:#222;
                    color:#fff;
                    box-sizing:border-box;
                "
            >

            <small style="color:#aaa; display:block; margin-top:5px;">
                Nomor member dibuat otomatis dan masih bisa diedit.
            </small>

        </div>

        <!-- PILIHAN KELAS -->
        <div style="margin-bottom:20px;">

            <label
                for="cabor"
                style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                    font-size:0.9rem;
                    color:#fff;
                "
            >
                CABOR / PILIHAN KELAS
            </label>

            <select
                id="cabor"
                name="cabor"
                required
                style="
                    width:100%;
                    padding:12px;
                    border-radius:4px;
                    border:1px solid #333;
                    background:#222;
                    color:#fff;
                    box-sizing:border-box;
                "
            >

                <option value="">-- Pilih Kelas --</option>

                <?php foreach ($pilihanKelas as $kelas): ?>

                    <option
                        value="<?php echo htmlspecialchars($kelas['judul']); ?>"
                        <?php echo (int) $kelas['stok'] <= 0 ? 'disabled' : ''; ?>
                    >

                        <?php echo htmlspecialchars($kelas['judul']); ?>

                        (Sisa kuota: <?php echo (int) $kelas['stok']; ?>)

                    </option>

                <?php endforeach; ?>

            </select>

            <?php if (empty($pilihanKelas)): ?>

                <small style="color:#e50914; display:block; margin-top:8px;">
                    Belum ada jadwal kelas. Tambahkan kelas terlebih dahulu.
                </small>

            <?php endif; ?>

        </div>

        <!-- NOMOR HP -->
        <div style="margin-bottom:20px;">

            <label
                for="no_hp"
                style="
                    display:block;
                    margin-bottom:8px;
                    font-weight:bold;
                    font-size:0.9rem;
                    color:#fff;
                "
            >
                NO. HP / WHATSAPP
            </label>

            <input
                type="text"
                id="no_hp"
                name="no_hp"
                placeholder="Contoh: 08123456789"
                style="
                    width:100%;
                    padding:12px;
                    border-radius:4px;
                    border:1px solid #333;
                    background:#222;
                    color:#fff;
                    box-sizing:border-box;
                "
            >

        </div>

        <!-- TOMBOL -->
        <div style="margin-top:25px;">

            <button
                type="submit"
                <?php echo empty($pilihanKelas) ? 'disabled' : ''; ?>
                style="
                    background:#e50914;
                    color:#fff;
                    padding:12px 30px;
                    border:none;
                    border-radius:4px;
                    font-weight:bold;
                    cursor:pointer;
                    font-size:1rem;
                "
            >
                SIMPAN MEMBER
            </button>

            <a
                href="list.php"
                style="
                    color:#aaa;
                    margin-left:15px;
                    text-decoration:none;
                    font-size:0.9rem;
                "
            >
                Batal
            </a>

        </div>

    </form>

</section>

<?php include __DIR__ . '/../../includes/footer.php'; ?>