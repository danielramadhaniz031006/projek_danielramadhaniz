<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

// =========================================================
// 1. PROSES SIMPAN DATA MEMBER BARU (SEBELUM HEADER.PHP)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama'] ?? '');
    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $cabor      = trim($_POST['cabor'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    if ($nama !== '' && $no_anggota !== '') {
        $insertStmt = $pdo->prepare(
            "INSERT INTO anggota (no_anggota, nama, cabor, no_hp) VALUES (:no_anggota, :nama, :cabor, :no_hp)"
        );
        $insertStmt->execute([
            'no_anggota' => $no_anggota,
            'nama'       => $nama,
            'cabor'      => $cabor,
            'no_hp'      => $no_hp
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Member baru berhasil ditambahkan!'
        ];

        header('Location: list.php');
        exit;
    }
}

// =========================================================
// 2. LOGIKA OTOMATIS GENERATE / REKOMENDASI NO. KARTU MEMBER
// =========================================================
$stmt = $pdo->query("SELECT no_anggota FROM anggota ORDER BY id DESC LIMIT 1");
$last_member = $stmt->fetch(PDO::FETCH_ASSOC);

$next_no_anggota = 'A1'; // Default jika database masih kosong

if ($last_member && !empty($last_member['no_anggota'])) {
    $last_no = $last_member['no_anggota'];
    
    // Ekstrak angka dari ID member terakhir (misal 'A30' -> 30)
    preg_match('/\d+/', $last_no, $matches);
    
    if (!empty($matches[0])) {
        $next_number = (int)$matches[0] + 1; // Tambah 1 untuk member berikutnya
        
        // Ambil awalan/prefix huruf (misal 'A')
        $prefix = preg_replace('/\d+/', '', $last_no);
        $prefix = !empty($prefix) ? $prefix : 'A';
        
        // Gabungkan prefix + nomor baru (misal 'A' + 31 = 'A31')
        $next_no_anggota = $prefix . $next_number;
    }
}

// =========================================================
// 3. INCLUDE HEADER PHP
// =========================================================
$page_title = "Tambah Member";
include __DIR__ . '/../includes/header.php';
?>

<section style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="border-left: 4px solid #e50914; padding-left: 12px; margin-bottom: 25px;">
        <h2 style="margin: 0; font-size: 1.8rem; font-weight: 800; text-transform: uppercase; color: #fff;">TAMBAH MEMBER</h2>
    </div>

    <form method="POST" style="background: #181818; padding: 25px; border-radius: 8px; border: 1px solid #282828;">
        
        <!-- NAMA MEMBER -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;">NAMA MEMBER</label>
            <input type="text" name="nama" placeholder="Contoh: Daniel Ramadhani Zulkarnain" required style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; box-sizing: border-box;">
        </div>

        <!-- ID MEMBER / NO. KARTU -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;">ID MEMBER / NO. KARTU</label>
            <input type="text" name="no_anggota" value="<?php echo htmlspecialchars($next_no_anggota); ?>" required style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; font-weight: bold; box-sizing: border-box;">
            <small style="color: #aaa; font-size: 0.8rem; margin-top: 5px; display: block;">*Otomatis merekomendasikan nomor kartu berikutnya (tetap bisa diedit manual jika diperlukan).</small>
        </div>

        <!-- CABOR / PILIHAN KELAS -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;">CABOR / PILIHAN KELAS</label>
            <select name="cabor" style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; box-sizing: border-box;">
                <option value="Muay Thai Striking">Muay Thai Striking</option>
                <option value="Boxing Pad Work">Boxing Pad Work</option>
                <option value="Fit Boxing & Cardio">Fit Boxing & Cardio</option>
                <option value="Brazilian Jiu-Jitsu (BJJ)">Brazilian Jiu-Jitsu (BJJ)</option>
                <option value="Strength & Conditioning">Strength & Conditioning</option>
                <option value="Wrestling & Takedown">Wrestling & Takedown</option>
            </select>
        </div>

        <!-- NO. HP / WHATSAPP -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;">NO. HP / WHATSAPP</label>
            <input type="text" name="no_hp" placeholder="Contoh: 08123456789" style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; box-sizing: border-box;">
        </div>

        <!-- TOMBOL AKSI -->
        <div style="margin-top: 25px;">
            <button type="submit" style="background: #e50914; color: #fff; padding: 12px 30px; border: none; border-radius: 4px; font-weight: bold; text-transform: uppercase; cursor: pointer; font-size: 1rem;">SIMPAN</button>
            <a href="list.php" style="color: #aaa; margin-left: 15px; text-decoration: none; font-size: 0.9rem;">Batal</a>
        </div>

    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>