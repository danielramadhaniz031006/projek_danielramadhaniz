<?php

session_start();

/* =========================================================
   ELRAM TRAINING CAMP
   HALAMAN BERANDA
   ========================================================= */


/* =========================================================
   1. KONEKSI DATABASE
   ========================================================= */

require_once __DIR__ . '/includes/koneksi.php';


/* =========================================================
   2. RINGKASAN STATISTIK
   ========================================================= */

// Total kelas
$totalKelas = (int) $pdo
    ->query("
        SELECT COUNT(*)
        FROM public.jadwal_kelas
    ")
    ->fetchColumn();


// Total member
$totalAnggota = (int) $pdo
    ->query("
        SELECT COUNT(*)
        FROM public.anggota
    ")
    ->fetchColumn();


// Total coach unik
$totalCoach = (int) $pdo
    ->query("
        SELECT COUNT(DISTINCT nama_coach)
        FROM public.jadwal_kelas
        WHERE nama_coach IS NOT NULL
          AND TRIM(nama_coach) <> ''
    ")
    ->fetchColumn();


// Total kapasitas semua kelas
$totalKapasitas = (int) $pdo
    ->query("
        SELECT COALESCE(SUM(kapasitas), 0)
        FROM public.jadwal_kelas
    ")
    ->fetchColumn();


// Hitung sisa kuota berdasarkan jumlah member
$stmtKuota = $pdo->query("
    SELECT COALESCE(
        SUM(
            GREATEST(
                j.kapasitas - COALESCE(m.jumlah_member, 0),
                0
            )
        ),
        0
    )
    FROM public.jadwal_kelas j

    LEFT JOIN (
        SELECT
            jadwal_kelas_id,
            COUNT(*) AS jumlah_member
        FROM public.anggota
        GROUP BY jadwal_kelas_id
    ) m
        ON m.jadwal_kelas_id = j.id
");

$totalKuota = (int) $stmtKuota->fetchColumn();


/* =========================================================
   3. DAFTAR COACH DAN KELAS
   ========================================================= */

$stmtCoach = $pdo->query("
    SELECT
        nama_coach,

        STRING_AGG(
            nama_kelas,
            ', ' ORDER BY nama_kelas
        ) AS daftar_cabor,

        COUNT(*) AS total_kelas

    FROM public.jadwal_kelas

    WHERE nama_coach IS NOT NULL
      AND TRIM(nama_coach) <> ''

    GROUP BY nama_coach

    ORDER BY nama_coach ASC
");

$daftarCoach = $stmtCoach->fetchAll(PDO::FETCH_ASSOC);


/* =========================================================
   4. HEADER HALAMAN
   ========================================================= */

$page_title = "Beranda";

include __DIR__ . '/includes/header.php';

?>


<!-- =========================================================
     SELAMAT DATANG
     ========================================================= -->

<section class="welcome-section">

    <h2>
        Selamat Datang di ELRAM Training Camp
    </h2>

    <p style="font-weight: bold;">

        Selamat datang di ELRAM Training Camp, tempat terbaik untuk
        menempa fisik, disiplin, dan kemampuan bela diri kamu!

        Kami menyediakan berbagai cabang olahraga pilihan seperti
        Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA.

        Terbuka untuk semua tingkat kemampuan, dari pemula hingga mahir.

    </p>

</section>


<!-- =========================================================
     5. RINGKASAN STATISTIK
     ========================================================= -->

<section class="summary-section">

    <h2>
        Ringkasan Statistik Training Camp
    </h2>


    <!-- TOTAL KELAS -->

    <article class="stat-card">

        <h3>Total Kelas Cabor</h3>

        <p>
            <?php echo $totalKelas; ?>
        </p>

    </article>


    <!-- TOTAL MEMBER -->

    <article class="stat-card">

        <h3>Total Member</h3>

        <p>
            <?php echo $totalAnggota; ?>
        </p>

    </article>


    <!-- TOTAL COACH -->

    <article class="stat-card">

        <h3>Total Coach</h3>

        <p>
            <?php echo $totalCoach; ?>
        </p>

    </article>


    <!-- SISA KUOTA -->

    <article class="stat-card">

        <h3>Sisa Kuota Slot</h3>

        <p>
            <?php echo $totalKuota; ?>
        </p>

    </article>

</section>


<!-- =========================================================
     6. DAFTAR COACH DAN PELATIH
     ========================================================= -->

<section
    class="coach-section"
    style="margin-top: 35px;"
>

    <h2>
        Tim Coach &amp; Pelatih
    </h2>


    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        margin-top: 15px;
    ">


        <?php if (!empty($daftarCoach)): ?>

            <?php foreach ($daftarCoach as $coach): ?>

                <?php

                // Menghapus awalan Coach untuk membuat avatar
                $namaAvatar = preg_replace(
                    '/^Coach\s+/i',
                    '',
                    trim($coach['nama_coach'])
                );

                // Mengambil inisial coach
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


                        <!-- AVATAR -->

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

                            <?php
                            echo htmlspecialchars($inisial);
                            ?>

                        </div>


                        <!-- NAMA COACH -->

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


                    <!-- CABANG OLAHRAGA -->

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
     7. DAFTAR HARGA KELAS
     ========================================================= -->

<section
    class="pricing-banner-section"
    style="margin-top: 35px;"
>

    <h2>
        Daftar Harga Kelas
    </h2>


    <div class="pricing-banner-wrapper">

        <img
            src="<?php
                echo htmlspecialchars(
                    $base . '/assets/images/harga.png'
                );
            ?>"
            alt="Daftar Harga Kelas ELRAM Training Camp"
            class="pricing-banner-img"
        >

    </div>

</section>


<?php

/* =========================================================
   8. FOOTER
   ========================================================= */

include __DIR__ . '/includes/footer.php';

?>