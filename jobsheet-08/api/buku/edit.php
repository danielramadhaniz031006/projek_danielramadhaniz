<?php
session_start();

require_once __DIR__ . '/../includes/koneksi.php';

$page_title = "Edit Jadwal Kelas";

// Ambil ID jadwal dari URL
$id = $_GET['id'] ?? null;

// Pastikan ID tersedia
if (!$id) {
    header('Location: list.php');
    exit;
}

// Ambil data jadwal berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);

$kelas = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika jadwal tidak ditemukan, kembali ke daftar
if (!$kelas) {
    header('Location: list.php');
    exit;
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul'] ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $tahun     = (int) ($_POST['tahun'] ?? 0);
    $stok      = (int) ($_POST['stok'] ?? 0);

    // Validasi input
    if ($judul !== '' && $pengarang !== '' && $stok >= 0) {
        try {
            $updateStmt = $pdo->prepare(
                "UPDATE buku
                 SET judul = :judul,
                     pengarang = :pengarang,
                     kategori = :kategori,
                     tahun = :tahun,
                     stok = :stok
                 WHERE id = :id"
            );

            $updateStmt->execute([
                'judul'     => $judul,
                'pengarang' => $pengarang,
                'kategori'  => $kategori,
                'tahun'     => $tahun,
                'stok'      => $stok,
                'id'        => $id
            ]);

            // Pesan berhasil
            $_SESSION['flash'] = [
                'type'  => 'success',
                'pesan' => 'Jadwal kelas berhasil diperbarui!'
            ];

            // Redirect sebelum HTML ditampilkan
            header('Location: list.php');
            exit;

        } catch (PDOException $e) {
            $error = "Gagal memperbarui jadwal kelas.";
        }
    } else {
        $error = "Nama kelas, coach, dan kuota harus diisi dengan benar.";
    }
}

// Header dipanggil setelah semua proses redirect
include __DIR__ . '/../includes/header.php';
?>

<section>
    <h2>EDIT JADWAL KELAS</h2>

    <?php if (isset($error)): ?>
        <div class="flash flash-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="max-width: 500px; margin-top: 20px;">

        <div style="margin-bottom: 15px;">
            <label for="judul">Nama Kelas / Cabor:</label>

            <input
                type="text"
                id="judul"
                name="judul"
                value="<?php echo htmlspecialchars($kelas['judul']); ?>"
                required
                style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;"
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label for="pengarang">Coach / Pelatih:</label>

            <input
                type="text"
                id="pengarang"
                name="pengarang"
                value="<?php echo htmlspecialchars($kelas['pengarang']); ?>"
                required
                style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;"
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label for="kategori">Hari Latihan:</label>

            <input
                type="text"
                id="kategori"
                name="kategori"
                value="<?php echo htmlspecialchars($kelas['kategori'] ?? ''); ?>"
                placeholder="Contoh: Senin & Rabu"
                style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;"
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label for="tahun">Jam Latihan (Angka Jam, misal 19):</label>

            <input
                type="number"
                id="tahun"
                name="tahun"
                value="<?php echo htmlspecialchars((string) $kelas['tahun']); ?>"
                min="0"
                max="24"
                style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;"
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label for="stok">Sisa Kuota:</label>

            <input
                type="number"
                id="stok"
                name="stok"
                value="<?php echo htmlspecialchars((string) $kelas['stok']); ?>"
                min="0"
                style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;"
            >
        </div>

        <button
            type="submit"
            style="background:#e50914; color:#fff; padding:10px 20px; border:none; border-radius:4px; font-weight:bold; cursor:pointer;"
        >
            Simpan Perubahan
        </button>

        <a
            href="list.php"
            style="color:#aaa; margin-left:10px; text-decoration:none;"
        >
            Batal
        </a>

    </form> 
</section>

<?php
include __DIR__ . '/../includes/footer.php';
?>