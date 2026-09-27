<?php
session_start();
$page_title = "Daftar Member Gym";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

// Ambil pencarian
$keyword = trim($_GET['search'] ?? '');

if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM anggota WHERE nama LIKE :search OR no_anggota LIKE :search OR cabor LIKE :search ORDER BY id DESC");
    $stmt->execute(['search' => "%$keyword%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM anggota ORDER BY id DESC");
}

$daftar_anggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="padding: 12px; margin-bottom: 20px; border-radius: 4px; background: <?php echo $_SESSION['flash']['type'] === 'success' ? '#2e7d32' : '#c62828'; ?>; color: #fff;">
            <?php 
                echo $_SESSION['flash']['pesan']; 
                unset($_SESSION['flash']);
            ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>DAFTAR MEMBER</h2>
        <a href="tambah.php" style="background: #e50914; color: #fff; padding: 10px 18px; border-radius: 4px; text-decoration: none; font-weight: bold;">+ Tambah Member</a>
    </div>

    <!-- Form Pencarian -->
    <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari nama, ID member, atau cabor..." style="flex: 1; padding: 8px 12px; border-radius: 4px; border: 1px solid #444; background: #222; color: #fff;">
        <button type="submit" style="background: #333; color: #fff; padding: 8px 16px; border: 1px solid #555; border-radius: 4px; cursor: pointer;">Cari</button>
        <?php if ($keyword !== ''): ?>
            <a href="list.php" style="background: #555; color: #fff; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">Reset</a>
        <?php endif; ?>
    </form>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: #181818; color: #fff; text-align: left;">
            <thead>
                <tr style="background: #252525; border-bottom: 2px solid #333;">
                    <th style="padding: 12px;">No</th>
                    <th style="padding: 12px;">ID Member</th>
                    <th style="padding: 12px;">Nama Member</th>
                    <th style="padding: 12px;">Cabor / Class</th>
                    <th style="padding: 12px;">No. HP / WA</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($daftar_anggota) > 0): ?>
                    <?php foreach ($daftar_anggota as $index => $anggota): ?>
                        <tr style="border-bottom: 1px solid #2a2a2a;">
                            <td style="padding: 12px;"><?php echo $index + 1; ?></td>
                            <td style="padding: 12px; font-weight: bold;"><?php echo htmlspecialchars($anggota['no_anggota']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($anggota['nama']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($anggota['cabor']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($anggota['no_hp']); ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="edit.php?id=<?php echo $anggota['id']; ?>" style="background: #f39c12; color: #fff; padding: 6px 14px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.85rem;">Edit</a>
                                    <a href="hapus.php?id=<?php echo $anggota['id']; ?>" onclick="return confirm('Yakin ingin menghapus member <?php echo htmlspecialchars($anggota['nama']); ?>?');" style="background: #e50914; color: #fff; padding: 6px 14px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.85rem;">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #888;">Tidak ada data member yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>