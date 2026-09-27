<?php
session_start();
$page_title = "Edit Member";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

// Ambil data anggota berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    header('Location: list.php');
    exit;
}

// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $nama       = trim($_POST['nama'] ?? '');
    $cabor      = trim($_POST['cabor'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    if ($nama !== '') {
        $updateStmt = $pdo->prepare(
            "UPDATE anggota SET no_anggota = :no_anggota, nama = :nama, cabor = :cabor, no_hp = :no_hp WHERE id = :id"
        );
        $updateStmt->execute([
            'no_anggota' => $no_anggota,
            'nama'       => $nama,
            'cabor'      => $cabor,
            'no_hp'      => $no_hp,
            'id'         => $id
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Data member berhasil diperbarui!'
        ];

        header('Location: list.php');
        exit;
    }
}
?>

<section>
    <h2>EDIT DATA MEMBER</h2>

    <form method="POST" style="max-width: 500px; margin-top: 20px;">
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">ID Member (No. Anggota):</label>
            <input type="text" name="no_anggota" value="<?php echo htmlspecialchars($anggota['no_anggota']); ?>" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Nama Member:</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($anggota['nama']); ?>" required style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Cabor / Class:</label>
            <input type="text" name="cabor" value="<?php echo htmlspecialchars($anggota['cabor']); ?>" style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">No. HP / WA:</label>
            <input type="text" name="no_hp" value="<?php echo htmlspecialchars($anggota['no_hp']); ?>" style="width:100%; padding:8px; border-radius:4px; border:1px solid #444; background:#222; color:#fff;">
        </div>

        <button type="submit" style="background:#e50914; color:#fff; padding:10px 20px; border:none; border-radius:4px; font-weight:bold; cursor:pointer;">Simpan Perubahan</button>
        <a href="list.php" style="color:#aaa; margin-left:10px; text-decoration:none;">Batal</a>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>