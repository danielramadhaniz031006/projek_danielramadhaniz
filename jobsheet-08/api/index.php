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
$page_title = "Beranda Premium";
include __DIR__ . '/includes/header.php';

// TAMBAHAN STYLE UNTUK EFEK KACA DAN GRID (Masukkan ke dalam <style> di header atau file css terpisah)
?>
<style>
    /* Aksen Merah Utama */
    :root {
        --premium-red: #dc2626;
        --premium-red-hover: #b91c1c;
        --premium-dark: #090a0d;
        --premium-card-bg: rgba(22, 24, 30, 0.5);
        --premium-border: rgba(255, 255, 255, 0.08);
    }

    body {
        background-color: #030406 !important;
        background-image: radial-gradient(circle at 20% 30%, rgba(220, 38, 38, 0.05), transparent 50%),
                          radial-gradient(circle at 80% 80%, rgba(34, 197, 94, 0.05), transparent 50%);
    }

    /* Reset container agar lebih lebar dan dinamis */
    .main-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 20px;
    }

    /* =========================================================
       BENTO GRID LAYOUT
       ========================================================= */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Kotak Welcome (Span 8 kolom) */
    .bento-welcome {
        grid-column: span 8;
        background: linear-gradient(135deg, #181a20 0%, #0e0f13 100%);
        border: 1px solid var(--premium-border);
        border-radius: 20px;
        padding: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
    }
    
    /* Efek bayangan merah halus di pojok */
    .bento-welcome::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 100%; height: 100%;
        background: radial-gradient(circle, rgba(220, 38, 38, 0.15), transparent 70%);
        pointer-events: none;
    }

    .bento-welcome h2 {
        font-size: 2.5rem;
        font-weight: 800;
        text-transform: none;
        letter-spacing: normal;
        margin-bottom: 15px;
        border: none;
        padding: 0;
    }

    .bento-welcome p {
        color: #d1d5db;
        font-size: 1.1rem;
        max-width: 700px;
        line-height: 1.8;
    }

    /* Kotak Statistik (Span 4 kolom, Grid didalamnya) */
    .bento-stats {
        grid-column: span 4;
        background: var(--premium-card-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--premium-border);
        border-radius: 20px;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stats-inner-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        height: 100%;
    }

    .stat-box {
        background: rgba(9, 10, 13, 0.5);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 1px solid var(--premium-border);
        transition: transform 0.3s ease;
    }
    
    .stat-box:hover {
        transform: translateY(-3px);
        border-color: var(--premium-red);
    }

    .stat-box h3 {
        color: #9ca3af;
        font-size: 0.85rem;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .stat-box p {
        color: #fff;
        font-size: 2.5rem;
        font-weight: 900;
        line-height: 1;
    }
    
    /* Warna khusus untuk sisa kuota */
    .stat-box.quota-box p {
        color: #10b981; 
    }
    
    /* Warna khusus untuk total coach */
    .stat-box.coach-box p {
        color: #f59e0b; 
    }

    /* =========================================================
       GRID COACH & PELATIH (Tampilan Card Modern)
       ========================================================= */
    .coach-section {
        background: transparent;
        border: none;
        box-shadow: none;
        padding: 0;
    }
    
    .coach-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .coach-header-flex h2 {
        color: #fff;
        font-size: 1.8rem;
        margin: 0;
        border: none;
        padding: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .cert-badge {
        background: rgba(255,255,255,0.05);
        color: #9ca3af;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .coach-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .coach-card {
        background: linear-gradient(145deg, #181a20 0%, #0e0f13 100%);
        border: 1px solid var(--premium-border);
        border-radius: 16px;
        padding: 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Garis aksen merah tipis di atas card */
    .coach-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 3px;
        background: var(--premium-red);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .coach-card:hover {
        transform: translateY(-8px);
        border-color: rgba(220, 38, 38, 0.3);
        box-shadow: 0 15px 40px -15px rgba(0,0,0,0.7);
    }
    
    .coach-card:hover::after {
        transform: scaleX(1);
    }

    /* Header Card: Avatar & Nama */
    .card-header-flex {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .coach-avatar {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        background: linear-gradient(135deg, #20232b 0%, #16181e 100%);
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.5rem;
        border: 1px solid var(--premium-border);
        box-shadow: inset 0 1px 1px rgba(255,255,255,0.05);
        flex-shrink: 0;
    }

    .coach-name-block h4 {
        color: #fff;
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .coach-classes-tag {
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 4px;
    }

    /* Body Card: Cabor */
    .card-body-block {
        background: rgba(9, 10, 13, 0.5);
        border-radius: 10px;
        padding: 15px;
        border: 1px solid var(--premium-border);
    }

    .cabor-title {
        display: block;
        color: var(--premium-red);
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .cabor-list {
        color: #e5e7eb;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.5;
    }
    
    /* RESPONSIVE ADJUSTMENTS */
    @media (max-width: 1024px) {
        .bento-welcome, .bento-stats {
            grid-column: span 12;
        }
        .bento-welcome {
            padding: 30px;
        }
        .bento-welcome h2 {
            font-size: 2rem;
        }
    }
</style>

<!-- =========================================================
     STRUKTUR HTML UTAMA (Container Diperlebar)
     ========================================================= -->
<div class="main-container">

    <!-- BAGIAN ATAS: BENTO GRID (Welcome + Statistik) -->
    <div class="bento-grid">
        
        <!-- Kotak Sambutan Utama -->
        <article class="bento-welcome">
            <span style="display: inline-block; color: #ef4444; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">ELRAM TRAINING CAMP</span>
            <h2>Pusat Latihan Bela Diri Premium</h2>
            <p>
                Tempat terbaik untuk menempa fisik, disiplin, dan kemampuan bela diri kamu! 
                Kami menyediakan berbagai cabang olahraga pilihan seperti <strong>Muay Thai, Boxing, 
                Kickboxing, BJJ, hingga MMA</strong> — terbuka dari tingkat pemula hingga mahir.
            </p>
        </article>

        <!-- Kotak Statistik Dinamis -->
        <aside class="bento-stats">
            <h3 style="color: #fff; font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Ringkasan Camp</h3>
            
            <div class="stats-inner-grid">
                <div class="stat-box">
                    <h3>Total Kelas</h3>
                    <p><?php echo $totalKelas; ?></p>
                </div>
                
                <div class="stat-box">
                    <h3>Total Member</h3>
                    <p><?php echo $totalAnggota; ?></p>
                </div>
                
                <div class="stat-box coach-box">
                    <h3>Total Coach</h3>
                    <p><?php echo $totalCoach; ?></p>
                </div>
                
                <div class="stat-box quota-box">
                    <h3>Sisa Kuota</h3>
                    <p><?php echo $totalKuota; ?></p>
                </div>
            </div>
        </aside>

    </div>

    <!-- BAGIAN BAWAH: DAFTAR COACH (Card Modern) -->
    <section class="coach-section">
        <div class="coach-header-flex">
            <h2>Tim Coach & Pelatih Profesional</h2>
            <span class="cert-badge">🏆 Instruktur Bersertifikat</span>
        </div>

        <div class="coach-grid">
            <?php foreach ($daftarCoach as $coach): ?>
                <article class="coach-card">
                    
                    <div>
                        <!-- Header Card: Avatar & Nama -->
                        <header class="card-header-flex">
                            <div class="coach-avatar">
                                <?php echo strtoupper(substr(str_replace('Coach ', '', $coach['nama_coach']), 0, 1)); ?>
                            </div>
                            <div class="coach-name-block">
                                <h4><?php echo htmlspecialchars($coach['nama_coach']); ?></h4>
                                <span class="coach-classes-tag">
                                    ⚡ <?php echo $coach['total_kelas']; ?> Sesi Kelas
                                </span>
                            </div>
                        </header>
                    </div>

                    <!-- Body Card: Cabor -->
                    <div class="card-body-block">
                        <span class="cabor-title">Cabor Keahlian</span>
                        <p class="cabor-list"><?php echo htmlspecialchars($coach['daftar_cabor']); ?></p>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>
    </section>

</div>

<?php 
include __DIR__ . '/includes/footer.php';
?>