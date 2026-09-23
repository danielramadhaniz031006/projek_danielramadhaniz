<?php
$page_title = "Daftar Member";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Logika Pencarian Member
$cari = trim($_GET['cari'] ?? '');

if ($cari !== '') {
    $stmt = $pdo->prepare("SELECT * FROM anggota WHERE nama ILIKE :cari OR no_anggota ILIKE :cari ORDER BY id DESC");
    $stmt->execute(['cari' => '%' . $cari . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM anggota ORDER BY id DESC");
}

$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <h2>DAFTAR MEMBER</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </p>
    <?php endif; ?>

    <!-- FORM PENCARIAN -->
    <div class="search-box">
        <form method="get" action="list.php">
            <label for="search-input">CARI NAMA MEMBER:</label><br>
            <input 
                type="text" 
                id="search-input" 
                name="cari" 
                placeholder="Ketik nama atau ID member..." 
                value="<?php echo htmlspecialchars($cari); ?>"
            >
            <button type="submit" class="btn-reload" style="background-color: #e50914; color: white; border: none; padding: 6px 14px; font-weight: bold; cursor: pointer; border-radius: 4px;">
                CARI
            </button>
            <?php if ($cari !== ''): ?>
                <a href="list.php" style="color: #aaa; margin-left: 10px; text-decoration: underline;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL MEMBER -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID MEMBER</th>
                    <th>NAMA MEMBER</th>
                    <th>CABOR</th>
                    <th>NO. HP / WA</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #aaa;">
                            Belum ada data member. Silakan tambah lewat menu "Tambah Member".
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($anggota['no_anggota'] ?? $anggota['nomor_anggota'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($anggota['nama']); ?></td>
                            <!-- KOLOM ALAMAT DIGANTI MENJADI CABOR -->
                            <td><?php echo htmlspecialchars($anggota['cabor'] ?? $anggota['kategori'] ?? $anggota['alamat'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($anggota['no_hp']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $anggota['id']; ?>" class="btn-edit" style="background: #f39c12; color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.8rem; display: inline-block;">EDIT</a>
                                <a href="hapus.php?id=<?php echo $anggota['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus member ini?');" style="background: #e50914; color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.8rem; display: inline-block; margin-top: 2px;">HAPUS</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>