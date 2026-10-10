<?php
session_start();
$page_title = "Jadwal Kelas Gym";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$keyword = trim($_GET['search'] ?? '');
if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :search OR pengarang ILIKE :search OR kategori ILIKE :search ORDER BY id DESC");
    $stmt->execute(['search' => "%$keyword%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM buku ORDER BY id DESC");
}
$daftar_kelas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section style="max-width: 1100px; margin: 2rem auto; padding: 0 15px;">
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="padding: 12px; margin-bottom: 20px; border-radius: 6px; background: <?php echo $_SESSION['flash']['type'] === 'success' ? '#10b981' : '#dc2626'; ?>; color: #fff; font-weight: bold;">
            <?php echo $_SESSION['flash']['pesan']; unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #fff; margin: 0;">DAFTAR JADWAL KELAS</h2>
        <a href="tambah.php" style="background: #dc2626; color: #fff; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Tambah Kelas</a>
    </div>

    <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Cari nama kelas, pelatih, atau hari..." style="flex: 1; padding: 10px; border-radius: 6px; border: 1px solid #333; background: #111; color: #fff;">
        <button type="submit" style="background: #222; color: #fff; padding: 10px 20px; border: 1px solid #444; border-radius: 6px; cursor: pointer;">Cari</button>
        <?php if ($keyword !== ''): ?>
            <a href="list.php" style="background: #444; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none;">Reset</a>
        <?php endif; ?>
    </form>

    <div style="overflow-x: auto; background: #111318; padding: 20px; border-radius: 12px; border: 1px solid #222;">
        <table style="width: 100%; border-collapse: collapse; color: #fff; text-align: left;">
            <thead>
                <tr style="background: #090a0d; border-bottom: 2px solid #dc2626;">
                    <th style="padding: 12px;">NO</th>
                    <th style="padding: 12px;">NAMA KELAS / CABOR</th>
                    <th style="padding: 12px;">COACH / PELATIH</th>
                    <th style="padding: 12px;">HARI LATIHAN</th>
                    <th style="padding: 12px;">JAM LATIHAN</th>
                    <th style="padding: 12px; text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($daftar_kelas) > 0): ?>
                    <?php foreach ($daftar_kelas as $index => $kelas): ?>
                        <tr style="border-bottom: 1px solid #222630;">
                            <td style="padding: 12px; color: #888;"><?php echo $index + 1; ?></td>
                            <td style="padding: 12px; font-weight: bold;"><?php echo htmlspecialchars($kelas['judul']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($kelas['pengarang']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($kelas['kategori']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($kelas['tahun']); ?>:00 WIB</td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <a href="edit.php?id=<?php echo $kelas['id']; ?>" style="background: #f59e0b; color: #000; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.8rem;">Edit</a>
                                    <a href="hapus.php?id=<?php echo $kelas['id']; ?>" onclick="return confirm('Hapus kelas?');" style="background: #dc2626; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 0.8rem;">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="padding: 20px; text-align: center; color: #888;">Tidak ada data kelas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>