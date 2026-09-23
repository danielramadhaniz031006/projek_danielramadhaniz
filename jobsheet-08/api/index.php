<?php

$page_title = "Beranda";

include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';


/* =========================================================
   DATA RINGKASAN STATISTIK TRAINING CAMP
   ========================================================= */

// 1. Total Sesi Kelas / Cabor yang terdaftar di tabel buku
$totalKelas = $pdo
    ->query("SELECT COUNT(*) FROM buku")
    ->fetchColumn();

// 2. Total Member / Anggota yang terdaftar di tabel anggota
$totalAnggota = $pdo
    ->query("SELECT COUNT(*) FROM anggota")
    ->fetchColumn();

// 3. Total Coach / Pelatih Unik dari tabel buku
$totalCoach = $pdo
    ->query("SELECT COUNT(DISTINCT pengarang) FROM buku")
    ->fetchColumn();

// 4. Total Akumulasi Sisa Kuota Slot Latihan dari tabel buku
$totalKuota = $pdo
    ->query("SELECT COALESCE(SUM(stok), 0) FROM buku")
    ->fetchColumn();

// 5. Query Mengambil Daftar Coach dan Cabor yang Dipegang
$stmtCoach = $pdo->query("SELECT pengarang AS nama_coach, STRING_AGG(judul, ', ') AS daftar_cabor, COUNT(*) AS total_kelas FROM buku GROUP BY pengarang ORDER BY pengarang ASC");
$daftarCoach = $stmtCoach->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- =========================================================
     SELAMAT DATANG
     ========================================================= -->

<section class="welcome-section">

    <h2>
        Selamat Datang di ELRAM Training Camp
    </h2>

    <p style="font-weight: bold;">
        Selamat datang di ELRAM Training Camp, tempat terbaik untuk menempa fisik, disiplin, dan kemampuan bela diri kamu! Kami menyediakan berbagai cabang olahraga pilihan seperti Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA — terbuka dari tingkat pemula hingga mahir.
    </p>

</section>


<!-- =========================================================
     RINGKASAN STATISTIK DARI DATABASE
     ========================================================= -->

<section class="summary-section">

    <h2>Ringkasan Statistik Training Camp</h2>


    <!-- =====================================================
         TOTAL KELAS CABOR
         ===================================================== -->

    <article class="stat-card">

        <h3>
            Total Kelas Cabor
        </h3>

        <p>
            <?php echo $totalKelas; ?>
        </p>

    </article>


    <!-- =====================================================
         TOTAL MEMBER / ANGGOTA
         ===================================================== -->

    <article class="stat-card">

        <h3>
            Total Member
        </h3>

        <p>
            <?php echo $totalAnggota; ?>
        </p>

    </article>


    <!-- =====================================================
         TOTAL COACH / PELATIH
         ===================================================== -->

    <article class="stat-card">

        <h3>
            Total Coach
        </h3>

        <p>
            <?php echo $totalCoach; ?>
        </p>

    </article>


    <!-- =====================================================
         TOTAL KUOTA SLOT
         ===================================================== -->

    <article class="stat-card">

        <h3>
            Sisa Kuota Slot
        </h3>

        <p>
            <?php echo $totalKuota; ?>
        </p>

    </article>

</section>


<!-- =========================================================
     DAFTAR COACH & PELATIH
     ========================================================= -->

<section class="coach-section" style="margin-top: 35px;">

    <h2>Tim Coach & Pelatih</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-top: 15px;">
        <?php foreach ($daftarCoach as $coach): ?>
            <div style="background: #1f1f1f; padding: 18px; border-radius: 8px; border: 1px solid #333; display: flex; flex-direction: column;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #e50914; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; flex-shrink: 0;">
                        <?php echo strtoupper(substr(str_replace('Coach ', '', $coach['nama_coach']), 0, 1)); ?>
                    </div>
                    <div>
                        <h4 style="color: #fff; margin: 0; font-size: 0.95rem; font-weight: bold;">
                            <?php echo htmlspecialchars($coach['nama_coach']); ?>
                        </h4>
                        <span style="color: #888; font-size: 0.75rem;">
                            <?php echo $coach['total_kelas']; ?> Kelas Dipegang
                        </span>
                    </div>
                </div>
                <p style="color: #bbb; font-size: 0.82rem; margin: 0; line-height: 1.4; background: #141414; padding: 10px; border-radius: 6px; border: 1px solid #2a2a2a;">
                    <strong style="color: #e50914;">Cabor:</strong> <?php echo htmlspecialchars($coach['daftar_cabor']); ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

</section>


<?php

include __DIR__ . '/includes/footer.php';

?>