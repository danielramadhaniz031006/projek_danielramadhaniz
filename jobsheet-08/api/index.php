<?php
$page_title = 'Beranda';

// Koneksi database
require_once __DIR__ . '/includes/koneksi.php';

// Ambil total kelas
$stmtKelas = $pdo->query("SELECT COUNT(*) FROM buku");
$totalKelas = (int) $stmtKelas->fetchColumn();

// Ambil total member
$stmtAnggota = $pdo->query("SELECT COUNT(*) FROM anggota");
$totalAnggota = (int) $stmtAnggota->fetchColumn();

// Ambil total coach
$stmtCoach = $pdo->query(
    "SELECT COUNT(DISTINCT pengarang) FROM buku"
);
$totalCoach = (int) $stmtCoach->fetchColumn();

// Kuota tersedia berdasarkan stok kelas
$stmtKuota = $pdo->query(
    "SELECT COALESCE(SUM(stok), 0) FROM buku"
);
$totalKuota = (int) $stmtKuota->fetchColumn();

// Ambil daftar coach dan cabang olahraga yang diampu
$sqlCoach = "
    SELECT
        pengarang,
        COUNT(*) AS jumlah_kelas,
        STRING_AGG(judul, ', ' ORDER BY judul) AS daftar_cabor
    FROM buku
    GROUP BY pengarang
    ORDER BY pengarang
";

$stmtDaftarCoach = $pdo->query($sqlCoach);
$daftarCoach = $stmtDaftarCoach->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<!-- =========================================
     BERANDA ELRAM TRAINING CAMP
========================================= -->

<!-- BAGIAN SAMBUTAN -->
<section class="welcome-section">
    <h2>Selamat Datang di ELRAM Training Camp</h2>

    <p>
        Selamat datang di ELRAM Training Camp, tempat terbaik untuk
        menempa fisik, disiplin, dan kemampuan bela diri kamu!
        Kami menyediakan berbagai cabang olahraga pilihan seperti
        Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA —
        terbuka dari tingkat pemula hingga mahir.
    </p>
</section>


<!-- =========================================
     RINGKASAN STATISTIK
========================================= -->

<section class="summary-section">

    <h2>Ringkasan Statistik Training Camp</h2>

    <div class="stat-card">
        <h3>Total Kelas Cabor</h3>
        <p><?= $totalKelas ?></p>
    </div>

    <div class="stat-card">
        <h3>Total Member</h3>
        <p><?= $totalAnggota ?></p>
    </div>

    <div class="stat-card">
        <h3>Total Coach</h3>
        <p><?= $totalCoach ?></p>
    </div>

    <div class="stat-card">
        <h3>Sisa Kuota Slot</h3>
        <p><?= $totalKuota ?></p>
    </div>

</section>


<!-- =========================================
     TIM COACH DAN PELATIH
========================================= -->

<section class="coach-section">

    <h2>Tim Coach &amp; Pelatih</h2>

    <div class="coach-grid">

        <?php if (!empty($daftarCoach)): ?>

            <?php foreach ($daftarCoach as $coach): ?>

                <div class="coach-card">

                    <div class="coach-info">

                        <div class="coach-avatar">
                            <?= htmlspecialchars(
                                strtoupper(substr($coach['pengarang'], 0, 1))
                            ) ?>
                        </div>

                        <div>
                            <h3>
                                <?= htmlspecialchars($coach['pengarang']) ?>
                            </h3>

                            <p>
                                <?= (int) $coach['jumlah_kelas'] ?>
                                Kelas Dipegang
                            </p>
                        </div>

                    </div>

                    <div class="coach-cabor">
                        <strong>Cabor:</strong>
                        <?= htmlspecialchars($coach['daftar_cabor']) ?>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p>Belum ada data coach.</p>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================
     POSTER DAFTAR HARGA KELAS
========================================= -->

<section class="poster-harga-section">

    <div class="poster-harga-heading">

        <div>
            <h2>Daftar Harga Kelas ELRAM</h2>

            <p>
                Pilih cabang olahraga favoritmu dan mulai perjalanan
                latihanmu bersama ELRAM Training Camp.
            </p>
        </div>

    </div>

    <div class="poster-harga-container">

        <img
            src="../images/poster-harga-elram.png"
            alt="Poster Daftar Harga Kelas ELRAM Training Camp"
            class="poster-harga-image"
            loading="lazy"
        >

    </div>

</section>


<?php require_once __DIR__ . '/includes/footer.php'; ?>