<?php
session_start();
$page_title = "Edit Jadwal Kelas";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

// Ambil data berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$kelas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kelas) {
    header('Location: list.php');
    exit;
} 
 
// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul'] ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $tahun     = (int) ($_POST['tahun'] ?? 0);
    $stok      = (int) ($_POST['stok'] ?? 0);

    if ($judul !== '' && $pengarang !== '') {
        $updateStmt = $pdo->prepare(
            "UPDATE buku SET judul = :judul, pengarang = :pengarang, kategori = :kategori, tahun = :tahun, stok = :stok WHERE id = :id"
        );
        $updateStmt->execute([
            'judul'     => $judul,
            'pengarang' => $pengarang,
            'kategori'  => $kategori,
            'tahun'     => $tahun,
            'stok'      => $stok,
            'id'        => $id
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Jadwal kelas berhasil diperbarui!'
        ];

        header('Location: list.php');
        exit;
    }
}
?>

<section>
    <h2>EDIT JADWAL KELAS</h2>

    <form method="POST" style="max-width: 500px; margin-top: 20px;">
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Nama Kelas / Cabor:</label>
            <input type="text" name="judul" value="<?php echo htmlspecialchars($kelas['judul']); ?>" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Coach / Pelatih:</label>
            <input type="text" name="pengarang" value="<?php echo htmlspecialchars($kelas['pengarang']); ?>" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Hari Latihan:</label>
            <input type="text" name="kategori" value="<?php echo htmlspecialchars($kelas['kategori']); ?>" placeholder="Contoh: Senin & Rabu" style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Jam Latihan (Angka Jam, misal 19):</label>
            <input type="number" name="tahun" value="<?php echo htmlspecialchars($kelas['tahun']); ?>" min="0" max="24" style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Sisa Kuota:</label>
            <input type="number" name="stok" value="<?php echo htmlspecialchars($kelas['stok']); ?>" min="0" style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <button type="submit" style="background:#e50914; color:#fff; padding:10px 20px; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">Simpan Perubahan</button>
        <a href="list.php" style="color:#aaa; margin-left:10px; text-decoration:none;">Batal</a>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>