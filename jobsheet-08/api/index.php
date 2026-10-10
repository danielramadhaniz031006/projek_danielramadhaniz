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


// Total kapasitas seluruh kelas
$totalKapasitas = (int) $pdo
    ->query("
        SELECT COALESCE(SUM(kapasitas), 0)
        FROM public.jadwal_kelas
    ")
    ->fetchColumn();


// Hitung sisa kuota berdasarkan jumlah member setiap kelas
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
   3. DAFTAR COACH DAN KELAS YANG DIPEGANG
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
   4. DAFTAR HARGA KELAS
   ========================================================= */

$stmtHarga = $pdo->query("
    SELECT
        id,
        nama_kelas,
        nama_coach,
        hari_latihan,
        jam_latihan,
        harga

    FROM public.jadwal_kelas

    ORDER BY harga ASC, nama_kelas ASC
");

$daftarHarga = $stmtHarga->fetchAll(PDO::FETCH_ASSOC);


/* =========================================================
   5. HEADER HALAMAN
   ========================================================= */

$page_title = 'Beranda';

include __DIR__ . '/includes/header.php';

?>


<!-- =========================================================
     SELAMAT DATANG
     ========================================================= -->

<section class="welcome-section">

    <h2>
        Selamat Datang di ELRAM Training Camp
    </h2>

    <p style="font-weight:bold;">

        Selamat datang di ELRAM Training Camp, tempat terbaik untuk
        menempa fisik, disiplin, dan kemampuan bela diri kamu!

        Kami menyediakan berbagai cabang olahraga pilihan seperti
        Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA.

        Terbuka untuk semua tingkat kemampuan, dari pemula hingga mahir.

    </p>

</section>


<!-- =========================================================
     6. RINGKASAN STATISTIK
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
     7. DAFTAR COACH DAN PELATIH
     ========================================================= -->

<section class="coach-section" style="margin-top:35px;">

    <h2>
        Tim Coach &amp; Pelatih
    </h2>

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:15px;
        margin-top:15px;
    ">

        <?php if (!empty($daftarCoach)): ?>

            <?php foreach ($daftarCoach as $coach): ?>

                <?php

                // Menghilangkan awalan Coach dari inisial avatar
                $namaAvatar = preg_replace(
                    '/^Coach\s+/i',
                    '',
                    trim($coach['nama_coach'])
                );

                // Membuat inisial coach
                $inisial = strtoupper(
                    substr($namaAvatar, 0, 1)
                );

                ?>

                <article style="
                    background:linear-gradient(145deg,#20232b,#14161b);
                    padding:18px;
                    border-radius:8px;
                    border:1px solid #333744;
                    display:flex;
                    flex-direction:column;
                ">

                    <!-- INFORMASI COACH -->

                    <div style="
                        display:flex;
                        align-items:center;
                        gap:12px;
                        margin-bottom:12px;
                    ">

                        <!-- AVATAR -->

                        <div style="
                            width:40px;
                            height:40px;
                            border-radius:50%;
                            background:#dc2626;
                            color:#fff;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-weight:bold;
                            font-size:1.1rem;
                            flex-shrink:0;
                        ">

                            <?php
                            echo htmlspecialchars(
                                $inisial,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                        </div>


                        <!-- NAMA DAN JUMLAH KELAS -->

                        <div>

                            <h3 style="
                                color:#fff;
                                margin:0;
                                font-size:.95rem;
                                font-weight:800;
                            ">

                                <?php
                                echo htmlspecialchars(
                                    $coach['nama_coach'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            </h3>

                            <span style="
                                color:#9ca3af;
                                font-size:.8rem;
                            ">

                                <?php
                                echo (int) $coach['total_kelas'];
                                ?>

                                Kelas Dipegang

                            </span>

                        </div>

                    </div>


                    <!-- DAFTAR CABANG OLAHRAGA -->

                    <p style="
                        color:#d1d5db;
                        font-size:.85rem;
                        margin:0;
                        line-height:1.6;
                        background:#101115;
                        padding:12px;
                        border-radius:6px;
                        border:1px solid #2d313e;
                        overflow-wrap:anywhere;
                    ">

                        <strong style="color:#ef4444;">
                            Cabor:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $coach['daftar_cabor'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                    </p>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <p style="color:#9ca3af;">

                Belum ada data coach.

                Tambahkan jadwal kelas terlebih dahulu.

            </p>

        <?php endif; ?>

    </div>

</section>


<!-- =========================================================
     8. DAFTAR HARGA KELAS
     Harga diambil langsung dari database
     ========================================================= -->

<section class="pricing-banner-section">

    <h2>
        Daftar Harga Kelas
    </h2>

    <p style="
        color:#9ca3af;
        margin-bottom:20px;
        line-height:1.7;
    ">

        Temukan kelas latihan yang sesuai dengan minat
        dan kebutuhanmu di ELRAM Training Camp.

    </p>


    <!-- GRID KARTU HARGA -->

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:16px;
        margin-top:20px;
    ">

        <?php if (!empty($daftarHarga)): ?>

            <?php foreach ($daftarHarga as $item): ?>

                <?php

                $jamHarga = substr(
                    (string) $item['jam_latihan'],
                    0,
                    5
                );

                ?>

                <article style="
                    background:linear-gradient(145deg,#20232b,#14161b);
                    border:1px solid #333744;
                    border-top:3px solid #dc2626;
                    border-radius:10px;
                    padding:20px;
                    display:flex;
                    flex-direction:column;
                    gap:10px;
                    min-width:0;
                    transition:transform .2s ease,border-color .2s ease;
                ">

                    <!-- NAMA KELAS -->

                    <h3 style="
                        color:#fff;
                        font-size:1.05rem;
                        font-weight:800;
                        line-height:1.5;
                        overflow-wrap:anywhere;
                    ">

                        <?php
                        echo htmlspecialchars(
                            $item['nama_kelas'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                    </h3>


                    <!-- NAMA COACH -->

                    <p style="
                        color:#9ca3af;
                        font-size:.85rem;
                    ">

                        Coach:

                        <?php
                        echo htmlspecialchars(
                            $item['nama_coach'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                    </p>


                    <!-- JADWAL LATIHAN -->

                    <p style="
                        color:#d1d5db;
                        font-size:.85rem;
                        line-height:1.7;
                    ">

                        <?php
                        echo htmlspecialchars(
                            $item['hari_latihan'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                        <br>

                        Jam latihan:

                        <?php
                        echo htmlspecialchars(
                            $jamHarga,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                        WIB

                    </p>


                    <!-- GARIS PEMISAH -->

                    <div style="
                        height:1px;
                        background:#333744;
                        margin:4px 0;
                    "></div>


                    <!-- HARGA KELAS -->

                    <div style="margin-top:auto;">

                        <span style="
                            display:block;
                            color:#9ca3af;
                            font-size:.75rem;
                            text-transform:uppercase;
                            letter-spacing:1px;
                            margin-bottom:5px;
                        ">

                            Harga Kelas

                        </span>

                        <strong style="
                            display:block;
                            color:#ef4444;
                            font-size:1.5rem;
                            font-weight:900;
                            line-height:1.4;
                        ">

                            Rp<?php
                            echo number_format(
                                (float) $item['harga'],
                                0,
                                ',',
                                '.'
                            );
                            ?>

                        </strong>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <p style="
                color:#9ca3af;
                grid-column:1/-1;
            ">

                Belum ada data harga kelas.

                Tambahkan jadwal kelas terlebih dahulu.

            </p>

        <?php endif; ?>

    </div>

</section>


<?php

/* =========================================================
   9. FOOTER
   ========================================================= */

include __DIR__ . '/includes/footer.php';

?>