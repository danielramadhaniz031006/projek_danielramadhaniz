<?php

// =========================================================
// 1. KONEKSI DATABASE
// =========================================================

require_once __DIR__ . '/includes/koneksi.php';


// =========================================================
// 2. DATA RINGKASAN STATISTIK TRAINING CAMP
// =========================================================

// Total kelas / cabang olahraga
$totalKelas = (int) $pdo
    ->query("SELECT COUNT(*) FROM jadwal_kelas")
    ->fetchColumn();


// Total member
$totalAnggota = (int) $pdo
    ->query("SELECT COUNT(*) FROM anggota")
    ->fetchColumn();


// Total coach unik
$totalCoach = (int) $pdo
    ->query("
        SELECT COUNT(DISTINCT pengarang)
        FROM jadwal_kelas
        WHERE pengarang IS NOT NULL
          AND TRIM(pengarang) <> ''
    ")
    ->fetchColumn();


// Total sisa kuota dari seluruh kelas
$totalKuota = (int) $pdo
    ->query("
        SELECT COALESCE(SUM(stok), 0)
        FROM jadwal_kelas
    ")
    ->fetchColumn();


// =========================================================
// 3. DAFTAR COACH DAN CABOR YANG DIPEGANG
// =========================================================

$stmtCoach = $pdo->query("
    SELECT
        pengarang AS nama_coach,
        STRING_AGG(judul, ', ' ORDER BY judul) AS daftar_cabor,
        COUNT(*) AS total_kelas
    FROM jadwal_kelas
    WHERE pengarang IS NOT NULL
      AND TRIM(pengarang) <> ''
    GROUP BY pengarang
    ORDER BY pengarang ASC
");

$daftarCoach = $stmtCoach->fetchAll(PDO::FETCH_ASSOC);


// =========================================================
// 4. JUDUL HALAMAN DAN HEADER
// =========================================================

$page_title = "Beranda";

include __DIR__ . '/includes/header.php';

?>


<!-- =========================================================
     SELAMAT DATANG
     ========================================================= -->

<section class="welcome-section">

    <h2>Selamat Datang di ELRAM Training Camp</h2>

    <p style="font-weight: bold;">

        Selamat datang di ELRAM Training Camp, tempat terbaik untuk
        menempa fisik, disiplin, dan kemampuan bela diri kamu!

        Kami menyediakan berbagai cabang olahraga pilihan seperti
        Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA.

        Terbuka untuk semua tingkat kemampuan, dari pemula hingga mahir.

    </p>

</section>


<!-- =========================================================
     RINGKASAN STATISTIK TRAINING CAMP
     ========================================================= -->

<section class="summary-section">

    <h2>Ringkasan Statistik Training Camp</h2>

    <article class="stat-card">

        <h3>Total Kelas Cabor</h3>

        <p>
            <?php echo $totalKelas; ?>
        </p>

    </article>


    <article class="stat-card">

        <h3>Total Member</h3>

        <p>
            <?php echo $totalAnggota; ?>
        </p>

    </article>


    <article class="stat-card">

        <h3>Total Coach</h3>

        <p>
            <?php echo $totalCoach; ?>
        </p>

    </article>


    <article class="stat-card">

        <h3>Sisa Kuota Slot</h3>

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

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        margin-top: 15px;
    ">

        <?php if (!empty($daftarCoach)): ?>

            <?php foreach ($daftarCoach as $coach): ?>

                <?php

                // Ambil inisial nama coach untuk avatar
                $namaAvatar = preg_replace(
                    '/^Coach\s+/i',
                    '',
                    trim($coach['nama_coach'])
                );

                $inisial = strtoupper(
                    substr($namaAvatar, 0, 1)
                );

                ?>

                <div style="
                    background: #1f1f1f;
                    padding: 18px;
                    border-radius: 8px;
                    border: 1px solid #333;
                    display: flex;
                    flex-direction: column;
                ">

                    <!-- INFORMASI COACH -->
                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        margin-bottom: 12px;
                    ">

                        <!-- AVATAR COACH -->
                        <div style="
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            background: #e50914;
                            color: white;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: bold;
                            font-size: 1.1rem;
                            flex-shrink: 0;
                        ">

                            <?php echo htmlspecialchars($inisial); ?>

                        </div>


                        <!-- NAMA DAN JUMLAH KELAS -->
                        <div>

                            <h4 style="
                                color: #fff;
                                margin: 0;
                                font-size: 0.95rem;
                                font-weight: bold;
                            ">

                                <?php
                                echo htmlspecialchars(
                                    $coach['nama_coach']
                                );
                                ?>

                            </h4>


                            <span style="
                                color: #888;
                                font-size: 0.75rem;
                            ">

                                <?php
                                echo (int) $coach['total_kelas'];
                                ?>

                                Kelas Dipegang

                            </span>

                        </div>

                    </div>


                    <!-- CABANG OLAHRAGA YANG DIPEGANG -->
                    <p style="
                        color: #bbb;
                        font-size: 0.82rem;
                        margin: 0;
                        line-height: 1.4;
                        background: #141414;
                        padding: 10px;
                        border-radius: 6px;
                        border: 1px solid #2a2a2a;
                    ">

                        <strong style="color: #e50914;">
                            Cabor:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $coach['daftar_cabor'] ?? ''
                        );
                        ?>

                    </p>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p style="color: #888;">

                Belum ada data coach.

                Tambahkan jadwal kelas terlebih dahulu.

            </p>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     DAFTAR HARGA KELAS
     ========================================================= -->

<section
    class="pricing-banner-section"
    style="margin-top: 35px;"
>

    <h2>Daftar Harga Kelas</h2>

    <div class="pricing-banner-wrapper">

        <img
            src="assets/images/harga.png"
            alt="Daftar Harga Kelas ELRAM Training Camp"
            class="pricing-banner-img"
        >

    </div>

</section>


<?php

// =========================================================
// 5. FOOTER
// =========================================================

include __DIR__ . '/includes/footer.php';

?>