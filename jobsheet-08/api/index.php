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

// Kapasitas Dasar = 70 jika stok belum di-set
$totalKapasitas = ($stokBuku > $totalAnggota) ? (int)$stokBuku : 70;

// KALKULASI DINAMIS SISA KUOTA SLOT (diperbaiki syntax error-nya)
$totalKuota = max(0, $totalKapasitas - (int)$totalAnggota);

// Daftar Coach dan Cabor yang Dipegang
$stmtCoach = $pdo->query("SELECT pengarang AS nama_coach, STRING_AGG(judul, ', ') AS daftar_cabor, COUNT(*) AS total_kelas FROM buku GROUP BY pengarang ORDER BY pengarang ASC");
$daftarCoach = $stmtCoach->fetchAll(PDO::FETCH_ASSOC);

// 2. Set judul halaman lalu panggil header HTML
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';
?>

<!-- =========================================================
     SELAMAT DATANG
     ========================================================= -->
<section class="welcome-section">
    <h2>Selamat Datang di ELRAM Training Camp</h2>
    <p style="font-weight: bold;">
        Selamat datang di ELRAM Training Camp, tempat terbaik untuk menempa fisik, disiplin, dan kemampuan bela diri kamu! Kami menyediakan berbagai cabang olahraga pilihan seperti Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA — terbuka dari tingkat pemula hingga mahir.
    </p>
</section>

<!-- =========================================================
     RINGKASAN STATISTIK DARI DATABASE
     ========================================================= -->
<section class="summary-section">
    <h2>Ringkasan Statistik Training Camp</h2>

    <article class="stat-card">
        <h3>Total Kelas Cabor</h3>
        <p><?php echo $totalKelas; ?></p>
    </article>

    <article class="stat-card">
        <h3>Total Member</h3>
        <p><?php echo $totalAnggota; ?></p>
    </article>

    <article class="stat-card">
        <h3>Total Coach</h3>
        <p><?php echo $totalCoach; ?></p>
    </article>

    <article class="stat-card">
        <h3>Sisa Kuota Slot</h3>
        <p><?php echo $totalKuota; ?></p>
    </article>
</section>

<!-- =========================================================
     DAFTAR COACH & PELATIH
     ========================================================= -->
<section class="coach-section">
    <h2>Tim Coach & Pelatih</h2>

    <div class="coach-grid">
        <?php foreach ($daftarCoach as $coach): ?>
            <div class="coach-card">
                <div class="coach-header">
                    <div class="coach-avatar">
                        <?php echo strtoupper(substr(str_replace('Coach ', '', $coach['nama_coach']), 0, 1)); ?>
                    </div>
                    <div class="coach-info">
                        <h4><?php echo htmlspecialchars($coach['nama_coach']); ?></h4>
                        <span><?php echo $coach['total_kelas']; ?> Kelas Dipegang</span>
                    </div>
                </div>
                <p class="coach-cabor">
                    <strong style="color: var(--primary-light);">Cabor:</strong> <?php echo htmlspecialchars($coach['daftar_cabor']); ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php 
include __DIR__ . '/includes/footer.php';
?>