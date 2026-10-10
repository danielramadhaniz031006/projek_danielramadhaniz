<?php
// 1. Muat koneksi database dan jalankan query terlebih dahulu
require_once __DIR__ . '/includes/koneksi.php';

/* =========================================================
    DATA RINGKASAN STATISTIK TRAINING CAMP
    ========================================================= */

// Total Sesi Kelas / Cabor
$totalKelas = $pdo
    ->query("SELECT COUNT(*) FROM buku")
    ->fetchColumn();

// Total Member / Anggota
$totalAnggota = $pdo
    ->query("SELECT COUNT(*) FROM anggota")
    ->fetchColumn();

// Total Coach / Pelatih Unik
$totalCoach = $pdo
    ->query("SELECT COUNT(DISTINCT pengarang) FROM buku")
    ->fetchColumn();

// Ambil Total Stok Kuota dari tabel buku (cabor)
$stokBuku = $pdo
    ->query("SELECT COALESCE(SUM(stok), 0) FROM buku")
    ->fetchColumn();

// Kapasitas Dasar
$totalKapasitas = ($stokBuku > $totalAnggota) ? (int)$stokBuku : 70;

// KALKULASI DINAMIS SISA KUOTA SLOT
$totalKuota = max(0, $totalKapasitas - (int)$totalAnggota);

// Daftar Coach dan Cabor yang Dipegang
$stmtCoach = $pdo->query("SELECT pengarang AS nama_coach, STRING_AGG(judul, ', ') AS daftar_cabor, COUNT(*) AS total_kelas FROM buku GROUP BY pengarang ORDER BY pengarang ASC");
$daftarCoach = $stmtCoach->fetchAll(PDO::FETCH_ASSOC);

// 2. Set judul halaman lalu panggil header HTML
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';
?>

<!-- =========================================================
     TATA LETAK BARU: HERO & STATISTIK DALAM SATU GRID DINAMIS
     ========================================================= -->
<div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 20px; margin-bottom: 25px; align-items: stretch;">
    
    <!-- Kolom Kiri: Selamat Datang / Banner -->
    <section class="welcome-section" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; background: linear-gradient(135deg, rgba(220, 38, 38, 0.18) 0%, var(--white) 100%); border-left: 5px solid var(--primary);">
        <div style="display: inline-block; color: #ef4444; font-size: 0.75rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 8px;">
            🔥 ELRAM TRAINING CAMP SYSTEM
        </div>
        <h2 style="font-size: 1.6rem; margin-bottom: 12px; color: #fff; border: none; padding: 0;">Pusat Latihan Bela Diri & Kebugaran</h2>
        <p style="color: #cbd5e1; font-size: 0.95rem; line-height: 1.6; margin: 0;">
            Tempat terbaik untuk menempa fisik, disiplin, dan kemampuan bela diri kamu! Menyediakan berbagai cabang olahraga pilihan seperti <strong>Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA</strong> dari tingkat pemula hingga mahir.
        </p>
    </section>

    <!-- Kolom Kanan: Ringkasan Statistik 2x2 Grid Kecil yang Padat -->
    <section class="summary-section" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between;">
        <h2 style="font-size: 1.1rem; margin-bottom: 12px; border-left: 3px solid var(--primary); padding-left: 8px;">Statistik Singkat</h2>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
            <div style="background: #111318; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; text-align: center;">
                <span style="display: block; color: var(--muted); font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">Total Kelas</span>
                <span style="color: #ef4444; font-size: 1.6rem; font-weight: 900;"><?php echo $totalKelas; ?></span>
            </div>
            
            <div style="background: #111318; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; text-align: center;">
                <span style="display: block; color: var(--muted); font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">Total Member</span>
                <span style="color: #ef4444; font-size: 1.6rem; font-weight: 900;"><?php echo $totalAnggota; ?></span>
            </div>
            
            <div style="background: #111318; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; text-align: center;">
                <span style="display: block; color: var(--muted); font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">Total Coach</span>
                <span style="color: #ef4444; font-size: 1.6rem; font-weight: 900;"><?php echo $totalCoach; ?></span>
            </div>
            
            <div style="background: #111318; padding: 12px; border-radius: 8px; border: 1px solid #2a2e39; text-align: center;">
                <span style="display: block; color: var(--muted); font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">Sisa Kuota</span>
                <span style="color: #10b981; font-size: 1.6rem; font-weight: 900;"><?php echo $totalKuota; ?></span>
            </div>
        </div>
    </section>

</div>

<!-- =========================================================
     DAFTAR COACH & PELATIH (Layout Kartu Elegan)
     ========================================================= -->
<section class="coach-section" style="margin-top: 25px; background: linear-gradient(145deg, #16181e, #0e0f13);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2 style="margin: 0; border-left: 4px solid var(--primary); padding-left: 10px;">Tim Coach & Pelatih Profesional</h2>
        <span style="color: var(--muted); font-size: 0.85rem;">Instruktur Bersertifikat</span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 15px;">
        <?php foreach ($daftarCoach as $coach): ?>
            <div style="background: #111318; padding: 16px; border-radius: 10px; border: 1px solid #252833; transition: all 0.2s ease; display: flex; flex-direction: column; justify-content: space-between;" onmouseover="this.style.borderColor='#ef4444'; this.style.transform='translateY(-3px)';" onmouseout="this.style.borderColor='#252833'; this.style.transform='translateY(0)';">
                
                <div>
                    <!-- Header Kartu: Avatar & Nama -->
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, #ef4444, #991b1b); color: white; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.1rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);">
                            <?php echo strtoupper(substr(str_replace('Coach ', '', $coach['nama_coach']), 0, 1)); ?>
                        </div>
                        <div>
                            <h4 style="color: #fff; margin: 0 0 2px 0; font-size: 1rem; font-weight: 800;">
                                <?php echo htmlspecialchars($coach['nama_coach']); ?>
                            </h4>
                            <span style="color: #f59e0b; font-size: 0.75rem; font-weight: bold; background: rgba(245, 158, 11, 0.1); padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(245, 158, 11, 0.2);">
                                ⚡ <?php echo $coach['total_kelas']; ?> Kelas Dipegang
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bagian Bawah Kartu: Cabor Keahlian -->
                <div style="background: #090a0d; padding: 10px; border-radius: 8px; border: 1px solid #1f232d;">
                    <span style="display: block; color: #ef4444; font-size: 0.65rem; font-weight: 900; letter-spacing: 0.5px; margin-bottom: 3px; text-transform: uppercase;">Cabor Keahlian:</span>
                    <p style="color: #d1d5db; font-size: 0.82rem; margin: 0; line-height: 1.4;">
                        <?php echo htmlspecialchars($coach['daftar_cabor']); ?>
                    </p>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php 
include __DIR__ . '/includes/footer.php';
?>