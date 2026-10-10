<?php
session_start();
$page_title = "Daftar Member Gym";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$keyword = trim($_GET['search'] ?? '');
if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM anggota WHERE nama ILIKE :search OR no_anggota ILIKE :search OR cabor ILIKE :search ORDER BY id DESC");
    $stmt->execute(['search' => "%$keyword%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM anggota ORDER BY id DESC");
}
$daftar_anggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section style="max-width: 1200px; margin: 2rem auto; padding: 0 15px;">
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="padding: 12px; margin-bottom: 20px; border-radius: 6px; background: <?php echo $_SESSION['flash']['type'] === 'success' ? '#10b981' : '#dc2626'; ?>; color: #fff; font-weight: bold;">
            <?php echo $_SESSION['flash']['pesan']; unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #fff; margin: 0;">DAFTAR MEMBER</h2>
        <a href="tambah.php" style="background: #dc2626; color: #fff; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 800; font-size: 0.85rem;">+ Tambah Member</a>
    </div>

    <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari nama, ID member, atau cabor..." style="flex: 1; padding: 10px; border-radius: 6px; border: 1px solid #2a2e39; background: #090a0d; color: #fff;">
        <button type="submit" style="background: #21242d; color: #fff; padding: 10px 20px; border: 1px solid #2a2e39; border-radius: 6px; font-weight: 700; cursor: pointer;">Cari</button>
        <?php if ($keyword !== ''): ?>
            <a href="list.php" style="background: #374151; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 700;">Reset</a>
        <?php endif; ?>
    </form>

    <div style="overflow-x: auto; background: linear-gradient(145deg, #16181e 0%, #0e0f13 100%); padding: 20px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.08);">
        <table style="width: 100%; border-collapse: collapse; color: #fff; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="background: #090a0d; border-bottom: 2px solid #dc2626;">
                    <th style="padding: 12px 10px;">NO</th>
                    <th style="padding: 12px 10px;">ID MEMBER</th>
                    <th style="padding: 12px 10px;">NAMA MEMBER</th>
                    <th style="padding: 12px 10px;">CABOR / CLASS</th>
                    <th style="padding: 12px 10px;">LEVEL</th>
                    <th style="padding: 12px 10px;">PAKET & BIAYA</th>
                    <th style="padding: 12px 10px;">STATUS</th>
                    <th style="padding: 12px 10px;">NO. HP / WA</th>
                    <th style="padding: 12px 10px; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($daftar_anggota) > 0): ?>
                    <?php foreach ($daftar_anggota as $index => $member): ?>
                        <tr style="border-bottom: 1px solid #222630;">
                            <td style="padding: 14px 10px; color: #9ca3af; font-weight: bold;"><?php echo $index + 1; ?></td>
                            <td style="padding: 14px 10px; font-weight: 800; color: #ef4444;"><?php echo htmlspecialchars($member['no_anggota']); ?></td>
                            <td style="padding: 14px 10px; font-weight: 800; color: #fff;"><?php echo htmlspecialchars($member['nama']); ?></td>
                            <td style="padding: 14px 10px; color: #d1d5db;"><?php echo htmlspecialchars($member['cabor']); ?></td>
                            <td style="padding: 14px 10px; color: #38bdf8; font-weight: 600; font-size: 0.85rem;"><?php echo htmlspecialchars($member['level'] ?? 'Pemula'); ?></td>
                            <td style="padding: 14px 10px; color: #f59e0b; font-weight: 700;">
                                <?php echo htmlspecialchars($member['paket'] ?? 'Regular'); ?>
                                <br>
                                <small style="color: #10b981; font-weight: 600;">Rp <?php echo number_format($member['harga_paket'] ?? 150000, 0, ',', '.'); ?></small>
                            </td>
                            <td style="padding: 14px 10px;">
                                <span style="background: rgba(16, 185, 129, 0.15); color: #10b981; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(16, 185, 129, 0.3);">
                                    ✓ <?php echo htmlspecialchars($member['status_bayar'] ?? 'LUNAS'); ?>
                                </span>
                            </td>
                            <td style="padding: 14px 10px; color: #d1d5db;"><?php echo htmlspecialchars($member['no_hp']); ?></td>
                            <td style="padding: 14px 10px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <a href="edit.php?id=<?php echo $member['id']; ?>" style="background: #f59e0b; color: #000; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 800; font-size: 0.75rem;">Edit</a>
                                    <a href="hapus.php?id=<?php echo $member['id']; ?>" onclick="return confirm('Yakin menghapus member?');" style="background: #dc2626; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 800; font-size: 0.75rem;">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="padding: 25px; text-align: center; color: #9ca3af;">Tidak ada data member.</td></tr>
                <?php endif; ?>
            </tbody>
        </table> 
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>