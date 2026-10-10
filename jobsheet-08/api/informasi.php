<?php

$page_title = "Informasi ELRAM Training Camp";

include __DIR__ . '/includes/header.php';

?>

<!-- =========================================================
     HERO SECTION
     ========================================================= -->

<section class="welcome-section" style="
    padding: 45px 35px;
    text-align: center;
">

    <p style="
        color: #ef4444;
        font-weight: bold;
        letter-spacing: 3px;
        margin-bottom: 15px;
    ">
        WELCOME TO ELRAM
    </p>

    <h2 style="
        border-left: none;
        padding-left: 0;
        font-size: 2.2rem;
        margin-bottom: 20px;
    ">
        ELRAM TRAINING CAMP
    </h2>

    <p style="
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.9;
    ">
        Tempat untuk mengembangkan kekuatan fisik, kedisiplinan,
        teknik bela diri, dan kepercayaan diri melalui latihan
        yang terarah. Temukan kelas yang sesuai dengan minat
        dan tujuan latihanmu.
    </p>

    <div style="margin-top: 30px;">

        <a href="/api/buku/list.php" style="
            display: inline-block;
            background: #e50914;
            color: #fff;
            padding: 13px 25px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            margin: 5px;
        ">
            LIHAT JADWAL KELAS
        </a>

        <a href="/api/anggota/tambah.php" style="
            display: inline-block;
            background: transparent;
            color: #fff;
            padding: 12px 25px;
            border: 1px solid #e50914;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            margin: 5px;
        ">
            DAFTAR MEMBER
        </a>

    </div>

</section>


<!-- =========================================================
     TENTANG KAMI
     ========================================================= -->

<section>

    <h2>TENTANG KAMI</h2>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        align-items: center;
    ">

        <div>

            <h3 style="
                color: #ef4444;
                margin-bottom: 15px;
                font-size: 1.3rem;
            ">
                Lebih Kuat, Lebih Disiplin
            </h3>

            <p style="
                color: #d1d5db;
                line-height: 1.9;
                margin-bottom: 15px;
            ">
                ELRAM Training Camp merupakan konsep tempat latihan
                bela diri dan kebugaran yang menyediakan berbagai
                pilihan kelas untuk anggota.
            </p>

            <p style="
                color: #d1d5db;
                line-height: 1.9;
            ">
                Melalui halaman website ini, pengunjung dapat melihat
                jadwal latihan, mengetahui pilihan kelas, dan
                melakukan pendaftaran sebagai member.
            </p>

        </div>

        <div style="
            background: #1f1f1f;
            border: 1px solid #383838;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
        ">

            <div style="
                font-size: 3.5rem;
                margin-bottom: 15px;
            ">
                🥊
            </div>

            <h3 style="
                color: #fff;
                margin-bottom: 10px;
            ">
                Train With Purpose
            </h3>

            <p style="
                color: #aaa;
                line-height: 1.8;
            ">
                Berlatih dengan tujuan, menjaga kedisiplinan,
                dan mengembangkan kemampuan secara bertahap.
            </p>

        </div>

    </div>

</section>


<!-- =========================================================
     CABANG OLAHRAGA
     ========================================================= -->

<section>

    <h2>CABANG OLAHRAGA</h2>

    <p style="
        color: #aaa;
        margin-bottom: 25px;
    ">
        Pilih jenis latihan yang sesuai dengan minat dan tujuanmu.
    </p>

    <?php

    $daftarCabor = [

        [
            'nama' => 'Muay Thai',
            'ikon' => '🥊',
            'deskripsi' => 'Latihan teknik pukulan, tendangan, lutut, dan siku.'
        ],

        [
            'nama' => 'Boxing',
            'ikon' => '🥊',
            'deskripsi' => 'Mengembangkan teknik pukulan, pertahanan, dan koordinasi.'
        ],

        [
            'nama' => 'Kickboxing',
            'ikon' => '🦵',
            'deskripsi' => 'Latihan kombinasi teknik pukulan dan tendangan.'
        ],

        [
            'nama' => 'Brazilian Jiu-Jitsu',
            'ikon' => '🤼',
            'deskripsi' => 'Mempelajari teknik grappling dan pengendalian posisi.'
        ],

        [
            'nama' => 'Wrestling',
            'ikon' => '💪',
            'deskripsi' => 'Latihan teknik takedown dan pengendalian lawan.'
        ],

        [
            'nama' => 'Strength & Conditioning',
            'ikon' => '🏋️',
            'deskripsi' => 'Mendukung peningkatan kekuatan dan kebugaran fisik.'
        ],

        [
            'nama' => 'Mixed Martial Arts',
            'ikon' => '🔥',
            'deskripsi' => 'Menggabungkan berbagai teknik bela diri dalam latihan.'
        ]

    ];

    ?>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    ">

        <?php foreach ($daftarCabor as $cabor): ?>

            <article style="
                background: #1b1b1b;
                border: 1px solid #333;
                border-radius: 10px;
                padding: 22px;
                transition: border-color 0.2s ease;
            ">

                <div style="
                    font-size: 2rem;
                    margin-bottom: 15px;
                ">
                    <?php echo $cabor['ikon']; ?>
                </div>

                <h3 style="
                    color: #ef4444;
                    font-size: 1.05rem;
                    margin-bottom: 12px;
                ">
                    <?php echo htmlspecialchars($cabor['nama']); ?>
                </h3>

                <p style="
                    color: #bbb;
                    line-height: 1.8;
                    font-size: 0.9rem;
                ">
                    <?php echo htmlspecialchars($cabor['deskripsi']); ?>
                </p>

            </article>

        <?php endforeach; ?>

    </div>

</section>


<!-- =========================================================
     VISI DAN MISI
     ========================================================= -->

<section>

    <h2>VISI & MISI</h2>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    ">

        <!-- VISI -->

        <div style="
            background: #1b1b1b;
            border: 1px solid #333;
            border-left: 4px solid #e50914;
            border-radius: 8px;
            padding: 25px;
        ">

            <h3 style="
                color: #ef4444;
                margin-bottom: 15px;
            ">
                VISI
            </h3>

            <p style="
                color: #d1d5db;
                line-height: 1.9;
            ">
                Menjadi tempat latihan bela diri dan kebugaran
                yang mendukung perkembangan kemampuan, kedisiplinan,
                kesehatan, serta kepercayaan diri setiap anggota.
            </p>

        </div>


        <!-- MISI -->

        <div style="
            background: #1b1b1b;
            border: 1px solid #333;
            border-left: 4px solid #e50914;
            border-radius: 8px;
            padding: 25px;
        ">

            <h3 style="
                color: #ef4444;
                margin-bottom: 15px;
            ">
                MISI
            </h3>

            <ul style="
                color: #d1d5db;
                padding-left: 20px;
                line-height: 2;
            ">

                <li>Mendukung peningkatan kemampuan bela diri.</li>

                <li>Mendorong gaya hidup aktif dan sehat.</li>

                <li>Membangun kedisiplinan dan sikap sportif.</li>

                <li>Menyediakan informasi kelas yang mudah diakses.</li>

            </ul>

        </div>

    </div>

</section>


<!-- =========================================================
     NILAI LATIHAN
     ========================================================= -->

<section>

    <h2>NILAI LATIHAN KAMI</h2>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        text-align: center;
    ">

        <div style="
            padding: 20px;
            background: #1b1b1b;
            border: 1px solid #333;
            border-radius: 8px;
        ">

            <h3 style="color: #ef4444; margin-bottom: 10px;">
                DISIPLIN
            </h3>

            <p style="color: #bbb; line-height: 1.7;">
                Menghargai waktu, aturan, dan proses latihan.
            </p>

        </div>

        <div style="
            padding: 20px;
            background: #1b1b1b;
            border: 1px solid #333;
            border-radius: 8px;
        ">

            <h3 style="color: #ef4444; margin-bottom: 10px;">
                KONSISTEN
            </h3>

            <p style="color: #bbb; line-height: 1.7;">
                Mengembangkan kemampuan melalui latihan teratur.
            </p>

        </div>

        <div style="
            padding: 20px;
            background: #1b1b1b;
            border: 1px solid #333;
            border-radius: 8px;
        ">

            <h3 style="color: #ef4444; margin-bottom: 10px;">
                SPORTIF
            </h3>

            <p style="color: #bbb; line-height: 1.7;">
                Mengutamakan rasa hormat dan sikap sportif.
            </p>

        </div>

        <div style="
            padding: 20px;
            background: #1b1b1b;
            border: 1px solid #333;
            border-radius: 8px;
        ">

            <h3 style="color: #ef4444; margin-bottom: 10px;">
                BERKEMBANG
            </h3>

            <p style="color: #bbb; line-height: 1.7;">
                Terus meningkatkan kemampuan dan kebugaran.
            </p>

        </div>

    </div>

</section>


<!-- =========================================================
     AJAKAN BERGABUNG
     ========================================================= -->

<section class="welcome-section" style="
    text-align: center;
    padding: 40px 25px;
">

    <h2 style="
        border-left: none;
        padding-left: 0;
    ">
        SIAP MEMULAI LATIHAN?
    </h2>

    <p style="
        color: #d1d5db;
        margin-bottom: 25px;
        line-height: 1.8;
    ">
        Temukan kelas yang sesuai dengan minatmu dan lihat
        jadwal latihan yang tersedia di ELRAM Training Camp.
    </p>

    <a href="/api/buku/list.php" style="
        display: inline-block;
        background: #e50914;
        color: #fff;
        padding: 13px 25px;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        margin: 5px;
    ">
        LIHAT JADWAL
    </a>

    <a href="/api/anggota/tambah.php" style="
        display: inline-block;
        background: #222;
        color: #fff;
        padding: 12px 25px;
        border: 1px solid #444;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        margin: 5px;
    ">
        DAFTAR MEMBER
    </a>

</section>


<?php

include __DIR__ . '/includes/footer.php';

?>