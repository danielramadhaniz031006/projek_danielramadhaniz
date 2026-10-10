<?php
// =========================================================
// 1. KONEKSI DATABASE
// =========================================================

require_once __DIR__ . '/includes/koneksi.php';


// =========================================================
// 2. DATA RINGKASAN STATISTIK TRAINING CAMP
// =========================================================

// Total Kelas / Cabang Olahraga
$totalKelas = (int) $pdo
    ->query("SELECT COUNT(*) FROM buku")
    ->fetchColumn();

// Total Member / Anggota
$totalAnggota = (int) $pdo
    ->query("SELECT COUNT(*) FROM anggota")
    ->fetchColumn();

// Total Coach / Pelatih Unik
$totalCoach = (int) $pdo
    ->query("SELECT COUNT(DISTINCT pengarang) FROM buku")
    ->fetchColumn();

// Kapasitas tetap Training Camp
$totalKapasitas = 70;

// Sisa Kuota Slot
$totalKuota = max(0, $totalKapasitas - $totalAnggota);


// =========================================================
// 3. DAFTAR COACH DAN CABANG OLAHRAGA
// =========================================================

$stmtCoach = $pdo->query("
    SELECT
        pengarang AS nama_coach,
        STRING_AGG(judul, ', ' ORDER BY judul) AS daftar_cabor,
        COUNT(*) AS total_kelas
    FROM buku
    GROUP BY pengarang
    ORDER BY pengarang ASC
");

$daftarCoach = $stmtCoach->fetchAll(PDO::FETCH_ASSOC);


// =========================================================
// 4. HEADER HALAMAN
// =========================================================

$page_title = "Beranda";

include __DIR__ . '/includes/header.php';
?>


<!-- =========================================================
     BAGIAN SELAMAT DATANG
========================================================= -->

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


<!-- =========================================================
     RINGKASAN STATISTIK TRAINING CAMP
========================================================= -->

<section class="summary-section">

    <h2>Ringkasan Statistik Training Camp</h2>

    <article class="stat-card">
        <h3>Total Kelas Cabor</h3>
        <p><?= $totalKelas ?></p>
    </article>

    <article class="stat-card">
        <h3>Total Member</h3>
        <p><?= $totalAnggota ?></p>
    </article>

    <article class="stat-card">
        <h3>Total Coach</h3>
        <p><?= $totalCoach ?></p>
    </article>

    <article class="stat-card">
        <h3>Sisa Kuota Slot</h3>
        <p><?= $totalKuota ?></p>
    </article>

</section>


<!-- =========================================================
     TIM COACH DAN PELATIH
========================================================= -->

<section class="coach-section">

    <h2>Tim Coach &amp; Pelatih</h2>

    <div class="coach-grid">

        <?php if (!empty($daftarCoach)): ?>

            <?php foreach ($daftarCoach as $coach): ?>

                <article class="coach-card">

                    <!-- INFORMASI COACH -->

                    <div class="coach-info">

                        <div class="coach-avatar">
                            <?php
                            $namaCoach = preg_replace(
                                '/^Coach\s+/i',
                                '',
                                $coach['nama_coach']
                            );

                            echo htmlspecialchars(
                                strtoupper(substr($namaCoach, 0, 1))
                            );
                            ?>
                        </div>

                        <div class="coach-detail">

                            <h3>
                                <?= htmlspecialchars($coach['nama_coach']) ?>
                            </h3>

                            <span>
                                <?= (int) $coach['total_kelas'] ?>
                                Kelas Dipegang
                            </span>

                        </div>

                    </div>


                    <!-- CABANG OLAHRAGA YANG DIPEGANG -->

                    <div class="coach-cabor">

                        <strong>Cabor:</strong>

                        <?= htmlspecialchars(
                            $coach['daftar_cabor'] ?? ''
                        ) ?>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <p>Belum ada data coach dan pelatih.</p>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     POSTER DAFTAR HARGA KELAS
========================================================= -->

<section class="pricing-banner-section">

    <div class="pricing-banner-heading">

        <div>
            <h2>Daftar Harga Kelas ELRAM</h2>

            <p>
                Temukan kelas yang sesuai dengan tujuan latihanmu.
                Pilih cabang olahraga favorit dan mulai berlatih
                bersama ELRAM Training Camp!
            </p>
        </div>

        <span class="pricing-badge">
            ELRAM TRAINING CAMP
        </span>

    </div>


    <div class="pricing-banner-wrapper">

        <img
            src="/jobsheet-08/assets/images/daftar-harga.png"
            alt="Poster Daftar Harga Kelas ELRAM Training Camp"
            class="pricing-banner-img"
            loading="lazy"
        >

    </div>

</section>


<?php
// =========================================================
// 5. FOOTER
// =========================================================

include __DIR__ . '/includes/footer.php';
?>