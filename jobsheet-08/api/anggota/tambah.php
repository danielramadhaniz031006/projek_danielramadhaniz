<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$stmtCabor = $pdo->query("SELECT id, judul, pengarang FROM buku ORDER BY judul ASC");
$daftarCabor = $stmtCabor->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama'] ?? '');
    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $cabor      = trim($_POST['cabor'] ?? '');
    $level      = trim($_POST['level'] ?? 'Pemula');
    $paket_info = trim($_POST['paket'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    $paket_parts = explode('|', $paket_info);
    $nama_paket  = $paket_parts[0] ?? 'Regular (1 Bulan)';
    $harga_paket = isset($paket_parts[1]) ? (int)$paket_parts[1] : 150000;

    if ($nama !== '' && $no_anggota !== '') {
        $insertStmt = $pdo->prepare(
            "INSERT INTO anggota (no_anggota, nama, cabor, level, paket, harga_paket, status_bayar, no_hp, tanggal_daftar) 
             VALUES (:no_anggota, :nama, :cabor, :level, :paket, :harga_paket, 'LUNAS', :no_hp, CURRENT_DATE)"
        );
        $insertStmt->execute([
            'no_anggota'  => $no_anggota,
            'nama'        => $nama,
            'cabor'       => $cabor,
            'level'       => $level,
            'paket'       => $nama_paket,
            'harga_paket' => $harga_paket,
            'no_hp'       => $no_hp
        ]);

        $updateStok = $pdo->prepare("UPDATE buku SET stok = GREATEST(0, stok - 1) WHERE judul = :cabor");
        $updateStok->execute([':cabor' => $cabor]);

        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Member baru berhasil terdaftar!'];
        header('Location: list.php');
        exit;
    }
}

$stmt = $pdo->query("SELECT no_anggota FROM anggota ORDER BY id DESC LIMIT 1");
$last_member = $stmt->fetch(PDO::FETCH_ASSOC);

$next_no_anggota = 'A1';
if ($last_member && !empty($last_member['no_anggota'])) {
    preg_match('/\d+/', $last_member['no_anggota'], $matches);
    if (!empty($matches[0])) {
        $next_number = (int)$matches[0] + 1;
        $prefix = preg_replace('/\d+/', '', $last_member['no_anggota']);
        $prefix = !empty($prefix) ? $prefix : 'A';
        $next_no_anggota = $prefix . $next_number;
    }
}

$page_title = "Tambah Member";
include __DIR__ . '/../includes/header.php';
?>

<section style="max-width: 650px; margin: 2rem auto; padding: 0 15px;">
    <div style="border-left: 4px solid #dc2626; padding-left: 14px; margin-bottom: 25px;">
        <h2 style="margin: 0; font-size: 1.6rem; font-weight: 800; color: #fff;">TAMBAH MEMBER BARU</h2>
        <p style="margin: 4px 0 0 0; color: #9ca3af; font-size: 0.85rem;">Isi formulir pendaftaran anggota ELRAM Training Camp</p>
    </div>

    <form method="POST" style="background: linear-gradient(145deg, #16181e 0%, #0e0f13 100%); padding: 30px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.08);">
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #fff; font-size: 0.85rem;">NAMA MEMBER *</label>
            <input type="text" name="nama" placeholder="Contoh: Daniel Ramadhani Zulkarnain" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #fff; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #fff; font-size: 0.85rem;">ID MEMBER / NO. KARTU *</label>
            <input type="text" name="no_anggota" value="<?php echo htmlspecialchars($next_no_anggota); ?>" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #ef4444; font-weight: 800; box-sizing: border-box;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #fff; font-size: 0.85rem;">CABOR / KELAS LATIHAN</label>
                <select name="cabor" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #fff; box-sizing: border-box;">
                    <?php foreach ($daftarCabor as $item): ?>
                        <option value="<?php echo htmlspecialchars($item['judul']); ?>">
                            <?php echo htmlspecialchars($item['judul']); ?> (Coach <?php echo htmlspecialchars($item['pengarang']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #fff; font-size: 0.85rem;">LEVEL KEAHLIAN</label>
                <select name="level" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #fff; box-sizing: border-box;">
                    <option value="Pemula (Beginner)">Pemula (Beginner)</option>
                    <option value="Menengah (Intermediate)">Menengah (Intermediate)</option>
                    <option value="Mahir (Advanced)">Mahir (Advanced)</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #fff; font-size: 0.85rem;">PAKET MEMBERSHIP & BIAYA</label>
                <select name="paket" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #f59e0b; font-weight: 700; box-sizing: border-box;">
                    <option value="Regular (1 Bulan)|150000">Regular (1 Bulan) — Rp 150.000</option>
                    <option value="Hemat (3 Bulan)|400000">Hemat (3 Bulan) — Rp 400.000</option>
                    <option value="VIP Unlimited|750000">VIP Unlimited — Rp 750.000</option>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #fff; font-size: 0.85rem;">NO. HP / WHATSAPP</label>
                <input type="text" name="no_hp" placeholder="Contoh: 08123456789" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #fff; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-top: 25px; display: flex; gap: 12px;">
            <button type="submit" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; padding: 12px 28px; border: none; border-radius: 8px; font-weight: 800; cursor: pointer;">
                ✓ SIMPAN MEMBER
            </button>
            <a href="list.php" style="background: #21242d; color: #9ca3af; padding: 12px 22px; border-radius: 8px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center;">
                BATAL
            </a>
        </div>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>