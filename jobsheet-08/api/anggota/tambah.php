<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

// =========================================================
// 1. AMBIL DAFTAR CABOR / KELAS DARI DATABASE (DINAMIS)
// =========================================================
$stmtCabor = $pdo->query("SELECT DISTINCT judul FROM buku ORDER BY judul ASC");
$daftarCabor = $stmtCabor->fetchAll(PDO::FETCH_COLUMN);

// Jika database kelas/buku masih kosong, gunakan pilihan default
if (empty($daftarCabor)) {
    $daftarCabor = [
        'Muay Thai Striking',
        'Boxing Pad Work',
        'Fit Boxing & Cardio',
        'Brazilian Jiu-Jitsu (BJJ)',
        'Strength & Conditioning',
        'Wrestling & Takedown'
    ];
}

// =========================================================
// 2. PROSES SIMPAN DATA MEMBER BARU (SEBELUM HEADER.PHP)
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
// 3. LOGIKA OTOMATIS GENERATE / REKOMENDASI NO. KARTU MEMBER
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
// 4. INCLUDE HEADER PHP
// =========================================================
$page_title = "Tambah Member";
include __DIR__ . '/../includes/header.php';
?>

<section style="max-width: 650px; margin: 2rem auto; padding: 0 15px;">
    <!-- TITLE HEADER -->
    <div style="border-left: 4px solid #dc2626; padding-left: 14px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 style="margin: 0; font-size: 1.6rem; font-weight: 800; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">TAMBAH MEMBER BARU</h2>
            <p style="margin: 4px 0 0 0; color: #9ca3af; font-size: 0.85rem;">Isi formulir pendaftaran anggota ELRAM Training Camp</p>
        </div>
        <span style="background: rgba(220, 38, 38, 0.15); color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(220, 38, 38, 0.3);">
            🔥 REGISTRATION
        </span>
    </div>

    <!-- FORM CONTAINER -->
    <form method="POST" style="background: linear-gradient(145deg, #16181e 0%, #0e0f13 100%); padding: 30px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        
        <!-- NAMA MEMBER -->
        <div style="margin-bottom: 22px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">
                NAMA MEMBER <span style="color: #ef4444;">*</span>
            </label>
            <input type="text" name="nama" placeholder="Contoh: Daniel Ramadhani Zulkarnain" required style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #fff; font-size: 0.95rem; outline: none; box-sizing: border-box; transition: border-color 0.2s;" onfocus="this.style.borderColor='#dc2626';" onblur="this.style.borderColor='#2a2e39';">
        </div>

        <!-- ID MEMBER / NO. KARTU -->
        <div style="margin-bottom: 22px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">
                ID MEMBER / NO. KARTU <span style="color: #ef4444;">*</span>
            </label>
            <input type="text" name="no_anggota" value="<?php echo htmlspecialchars($next_no_anggota); ?>" required style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #ef4444; font-weight: 800; font-size: 1rem; outline: none; box-sizing: border-box; transition: border-color 0.2s;" onfocus="this.style.borderColor='#dc2626';" onblur="this.style.borderColor='#2a2e39';">
            <small style="color: #9ca3af; font-size: 0.78rem; margin-top: 6px; display: block; line-height: 1.4;">
                *Rekomendasi ID otomatis berdasarkan urutan pendaftaran (dapat diedit manual).
            </small>
        </div>

        <!-- CABOR & LEVEL KEAHLIAN (GRID 2 KOLOM) -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 22px;">
            <!-- CABOR / PILIHAN KELAS -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">
                    CABOR / KELAS LATIHAN
                </label>
                <select name="cabor" style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #fff; font-size: 0.9rem; outline: none; box-sizing: border-box; cursor: pointer;">
                    <?php foreach ($daftarCabor as $caborItem): ?>
                        <option value="<?php echo htmlspecialchars($caborItem); ?>">
                            <?php echo htmlspecialchars($caborItem); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- LEVEL KEAHLIAN MEMBER -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">
                    LEVEL KEAHLIAN
                </label>
                <select name="level" style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #fff; font-size: 0.9rem; outline: none; box-sizing: border-box; cursor: pointer;">
                    <option value="Pemula">Pemula (Beginner)</option>
                    <option value="Menengah">Menengah (Intermediate)</option>
                    <option value="Mahir">Mahir (Advanced)</option>
                </select>
            </div>
        </div>

        <!-- PAKET MEMBERSHIP & NO HP (GRID 2 KOLOM) -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 22px;">
            <!-- PAKET MEMBERSHIP DENGAN HARGA -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">
                    PAKET MEMBERSHIP & BIAYA
                </label>
                <select name="paket" style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #f59e0b; font-weight: 700; font-size: 0.88rem; outline: none; box-sizing: border-box; cursor: pointer;">
                    <option value="Regular (1 Bulan) - Rp 150.000">Regular (1 Bulan) — Rp 150.000</option>
                    <option value="Hemat (3 Bulan) - Rp 400.000">Hemat (3 Bulan) — Rp 400.000</option>
                    <option value="VIP Unlimited - Rp 750.000">VIP Unlimited (All Access) — Rp 750.000</option>
                </select>
            </div>

            <!-- NO. HP / WHATSAPP -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">
                    NO. HP / WHATSAPP
                </label>
                <input type="text" name="no_hp" placeholder="Contoh: 08123456789" style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #2a2e39; background: #090a0d; color: #fff; font-size: 0.95rem; outline: none; box-sizing: border-box; transition: border-color 0.2s;" onfocus="this.style.borderColor='#dc2626';" onblur="this.style.borderColor='#2a2e39';">
            </div>
        </div>

        <!-- TOMBOL AKSI (SIMPAN & BATAL) -->
        <div style="margin-top: 30px; display: flex; align-items: center; gap: 12px;">
            <button type="submit" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; padding: 12px 28px; border: none; border-radius: 8px; font-weight: 800; text-transform: uppercase; cursor: pointer; font-size: 0.9rem; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4); transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(220, 38, 38, 0.6)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(220, 38, 38, 0.4)';">
                ✓ SIMPAN MEMBER
            </button>
            
            <a href="list.php" style="background: #21242d; color: #9ca3af; padding: 12px 22px; border-radius: 8px; text-decoration: none; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; transition: all 0.2s ease; border: 1px solid #2a2e39;" onmouseover="this.style.color='#fff'; this.style.backgroundColor='#2a2e39';" onmouseout="this.style.color='#9ca3af'; this.style.backgroundColor='#21242d';">
                BATAL
            </a>
        </div>

    </form>
</section> 

<?php include __DIR__ . '/../includes/footer.php'; ?>