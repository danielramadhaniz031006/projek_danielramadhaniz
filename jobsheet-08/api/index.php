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
     HERO / SELAMAT DATANG (Modern Athletic Banner)
     ========================================================= -->
<section class="welcome-section">
    <div class="welcome-badge">🔥 ELRAM TRAINING CAMP SYSTEM</div>
    <h2>Pusat Latihan Bela Diri & Kebugaran Premium</h2>
    <p>
        Selamat datang di ELRAM Training Camp, tempat terbaik untuk menempa fisik, disiplin, dan kemampuan bela diri kamu! Kami menyediakan berbagai cabang olahraga pilihan seperti <strong>Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA</strong> — terbuka dari tingkat pemula hingga mahir.
    </p>
</section>

<!-- =========================================================
     RINGKASAN STATISTIK TRAINING CAMP
     ========================================================= -->
<section class="summary-section">
    <h2>Ringkasan Statistik Training Camp</h2>

    <article class="stat-card">
        <div class="stat-icon">🥊</div>
        <div>
            <h3>Total Kelas Cabor</h3>
            <p><?php echo $totalKelas; ?></p>
        </div>
    </article>

    <article class="stat-card">
        <div class="stat-icon">👥</div>
        <div>
            <h3>Total Member</h3>
            <p><?php echo $totalAnggota; ?></p>
        </div>
    </article>

    <article class="stat-card">
        <div class="stat-icon">🏆</div>
        <div>
            <h3>Total Coach</h3>
            <p><?php echo $totalCoach; ?></p>
        </div>
    </article>

    <article class="stat-card">
        <div class="stat-icon">⚡</div>
        <div>
            <h3>Sisa Kuota Slot</h3>
            <p class="highlight-quota"><?php echo $totalKuota; ?></p>
        </div>
    </article>
</section>

<!-- =========================================================
     DAFTAR COACH & PELATIH
     ========================================================= -->
<section class="coach-section" style="margin-top: 35px;">
    <h2>Tim Coach & Pelatih Profesional</h2>
    <p style="color: var(--muted); margin-bottom: 20px; font-size: 0.95rem;">Para ahli berpengalaman yang siap membimbing fisik dan teknik bertarung Anda.</p>

    <div class="coach-grid">
        <?php foreach ($daftarCoach as $coach): ?>
            <div class="coach-card">
                <div class="coach-header">
                    <div class="coach-avatar">
                        <?php echo strtoupper(substr(str_replace('Coach ', '', $coach['nama_coach']), 0, 1)); ?>
                    </div>
                    <div>
                        <h4 class="coach-name">
                            <?php echo htmlspecialchars($coach['nama_coach']); ?>
                        </h4>
                        <span class="coach-badge">
                            🔥 <?php echo $coach['total_kelas']; ?> Kelas Dipegang
                        </span>
                    </div>
                </div>
                <div class="coach-body">
                    <span class="cabor-label">CABOR KEAHLIAN:</span>
                    <p class="cabor-text"><?php echo htmlspecialchars($coach['daftar_cabor']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php 
include __DIR__ . '/includes/footer.php';
?>