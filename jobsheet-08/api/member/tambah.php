<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Tambah Member';


/* =========================================================
   1. GENERATE NOMOR MEMBER OTOMATIS
   ========================================================= */

$stmtNomor = $pdo->query("
    SELECT nomor_member
    FROM public.anggota
    WHERE nomor_member ~ '[0-9]+$'
    ORDER BY
        substring(
            nomor_member from '([0-9]+)$'
        )::BIGINT DESC,
        id DESC
    LIMIT 1
");

$lastMember = $stmtNomor->fetch(PDO::FETCH_ASSOC);

$nextNomor = 'A1';

if (
    $lastMember &&
    preg_match(
        '/^(.*?)(\d+)$/',
        $lastMember['nomor_member'],
        $matches
    )
) {
    $nextNomor = $matches[1]
        . ((int) $matches[2] + 1);
}


/* =========================================================
   2. AMBIL DAFTAR KELAS DAN HARGA
   ========================================================= */

$stmtKelas = $pdo->query("
    SELECT
        j.id,
        j.nama_kelas,
        j.nama_coach,
        j.hari_latihan,
        j.jam_latihan,
        j.kapasitas,
        j.harga,

        COUNT(a.id) AS jumlah_member,

        GREATEST(
            j.kapasitas - COUNT(a.id),
            0
        ) AS sisa_kuota

    FROM public.jadwal_kelas j

    LEFT JOIN public.anggota a
        ON a.jadwal_kelas_id = j.id

    GROUP BY j.id

    ORDER BY j.nama_kelas ASC
");

$pilihanKelas = $stmtKelas->fetchAll(PDO::FETCH_ASSOC);


/* =========================================================
   3. NOTIFIKASI
   ========================================================= */

$flash = $_SESSION['flash'] ?? null;

unset($_SESSION['flash']);


/* =========================================================
   4. HEADER
   ========================================================= */

include __DIR__ . '/../includes/header.php';

?>


<!-- =========================================================
     FORM TAMBAH MEMBER
     ========================================================= -->

<section style="
    max-width:700px;
    margin:0 auto;
    padding:20px;
">

    <h2>TAMBAH MEMBER</h2>


    <!-- NOTIFIKASI -->

    <?php if ($flash): ?>

        <div class="flash <?php
            echo ($flash['type'] ?? '') === 'success'
                ? 'flash-success'
                : 'flash-error';
        ?>">

            <?php
            echo htmlspecialchars(
                $flash['pesan'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            );
            ?>

        </div>

    <?php endif; ?>


    <!-- FORM -->

    <form
        id="form-tambah"
        method="POST"
        action="proses_tambah.php"
        style="
            max-width:100%;
            background:#181818;
            padding:25px;
            border-radius:8px;
            border:1px solid #282828;
        "
    >


        <!-- =================================================
             NAMA MEMBER
             ================================================= -->

        <div style="margin-bottom:20px;">

            <label for="nama">
                NAMA MEMBER
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                placeholder="Masukkan nama lengkap member"
                required
            >

        </div>


        <!-- =================================================
             NOMOR MEMBER
             ================================================= -->

        <div style="margin-bottom:20px;">

            <label for="nomor_member">
                ID MEMBER / NOMOR KARTU
            </label>

            <input
                type="text"
                id="nomor_member"
                name="nomor_member"
                maxlength="20"
                value="<?php
                    echo htmlspecialchars(
                        $nextNomor,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?>"
                required
            >

            <small style="
                display:block;
                margin-top:6px;
                color:#9ca3af;
            ">
                Nomor member dibuat otomatis dan dapat diubah.
            </small>

        </div>


        <!-- =================================================
             PILIHAN CABOR / KELAS
             ================================================= -->

        <div style="margin-bottom:20px;">

            <label for="jadwal_kelas_id">
                CABOR / PILIHAN KELAS
            </label>

            <select
                id="jadwal_kelas_id"
                name="jadwal_kelas_id"
                required
            >

                <option value="">
                    -- Pilih Kelas --
                </option>


                <?php foreach ($pilihanKelas as $kelas): ?>

                    <?php

                    $jam = substr(
                        (string) $kelas['jam_latihan'],
                        0,
                        5
                    );

                    $sisaKuota = (int) $kelas['sisa_kuota'];

                    $hargaKelas = (float) $kelas['harga'];

                    ?>

                    <option
                        value="<?php echo (int) $kelas['id']; ?>"
                        data-harga="<?php echo $hargaKelas; ?>"
                        data-coach="<?php
                            echo htmlspecialchars(
                                $kelas['nama_coach'],
                                ENT_QUOTES,
                                'UTF-8'
                            );
                        ?>"
                        data-hari="<?php
                            echo htmlspecialchars(
                                $kelas['hari_latihan'],
                                ENT_QUOTES,
                                'UTF-8'
                            );
                        ?>"
                        data-jam="<?php
                            echo htmlspecialchars(
                                $jam,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                        ?>"
                        data-kuota="<?php echo $sisaKuota; ?>"
                        <?php echo $sisaKuota <= 0 ? 'disabled' : ''; ?>
                    >

                        <?php
                        echo htmlspecialchars(
                            $kelas['nama_kelas'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                        —

                        Rp<?php
                        echo number_format(
                            $hargaKelas,
                            0,
                            ',',
                            '.'
                        );
                        ?>

                        <?php if ($sisaKuota <= 0): ?>
                            (KUOTA PENUH)
                        <?php endif; ?>

                    </option>

                <?php endforeach; ?>

            </select>


            <?php if (empty($pilihanKelas)): ?>

                <small style="
                    display:block;
                    margin-top:8px;
                    color:#fca5a5;
                ">
                    Belum ada jadwal kelas. Tambahkan kelas terlebih dahulu.
                </small>

            <?php endif; ?>

        </div>


        <!-- =================================================
             INFORMASI KELAS YANG DIPILIH
             ================================================= -->

        <div
            id="informasi-harga"
            style="
                display:none;
                margin-bottom:20px;
                background:linear-gradient(145deg,#20232b,#14161b);
                border:1px solid #383c49;
                border-left:4px solid #dc2626;
                border-radius:8px;
                padding:18px;
            "
        >

            <h3 style="
                color:#fff;
                font-size:1rem;
                margin-bottom:14px;
            ">
                INFORMASI KELAS
            </h3>


            <!-- NAMA COACH -->

            <div style="
                display:flex;
                justify-content:space-between;
                gap:12px;
                margin-bottom:8px;
            ">

                <span style="color:#9ca3af;">
                    Coach / Pelatih
                </span>

                <strong
                    id="harga-coach"
                    style="color:#fff;text-align:right;"
                >
                    -
                </strong>

            </div>


            <!-- HARI LATIHAN -->

            <div style="
                display:flex;
                justify-content:space-between;
                gap:12px;
                margin-bottom:8px;
            ">

                <span style="color:#9ca3af;">
                    Hari Latihan
                </span>

                <strong
                    id="harga-hari"
                    style="color:#fff;text-align:right;"
                >
                    -
                </strong>

            </div>


            <!-- JAM LATIHAN -->

            <div style="
                display:flex;
                justify-content:space-between;
                gap:12px;
                margin-bottom:14px;
            ">

                <span style="color:#9ca3af;">
                    Jam Latihan
                </span>

                <strong
                    id="harga-jam"
                    style="color:#fff;text-align:right;"
                >
                    -
                </strong>

            </div>


            <!-- GARIS PEMISAH -->

            <div style="
                height:1px;
                background:#383c49;
                margin-bottom:14px;
            "></div>


            <!-- HARGA -->

            <span style="
                display:block;
                color:#9ca3af;
                font-size:.8rem;
                text-transform:uppercase;
                letter-spacing:1px;
                margin-bottom:5px;
            ">
                HARGA KELAS
            </span>

            <strong
                id="harga-nominal"
                style="
                    display:block;
                    color:#ef4444;
                    font-size:1.7rem;
                    font-weight:900;
                "
            >
                Rp0
            </strong>


            <!-- SISA KUOTA -->

            <span
                id="harga-kuota"
                style="
                    display:block;
                    margin-top:8px;
                    color:#9ca3af;
                    font-size:.85rem;
                "
            >
                Sisa kuota akan ditampilkan di sini.
            </span>

        </div>


        <!-- =================================================
             LEVEL MEMBER
             ================================================= -->

        <div style="margin-bottom:20px;">

            <label for="level">
                LEVEL MEMBER
            </label>

            <select
                id="level"
                name="level"
            >

                <option value="Pemula (Beginner)">
                    Pemula (Beginner)
                </option>

                <option value="Menengah (Intermediate)">
                    Menengah (Intermediate)
                </option>

                <option value="Mahir (Advanced)">
                    Mahir (Advanced)
                </option>

            </select>

        </div>


        <!-- =================================================
             NOMOR HP
             ================================================= -->

        <div style="margin-bottom:20px;">

            <label for="nomor_hp">
                NO. HP / WHATSAPP
            </label>

            <input
                type="tel"
                id="nomor_hp"
                name="nomor_hp"
                maxlength="20"
                placeholder="Contoh: 08123456789"
            >

        </div>


        <!-- =================================================
             TOMBOL
             ================================================= -->

        <div style="
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            margin-top:25px;
        ">

            <button
                type="submit"
                <?php echo empty($pilihanKelas) ? 'disabled' : ''; ?>
            >
                SIMPAN MEMBER
            </button>

            <a
                href="list.php"
                class="btn-secondary"
            >
                BATAL
            </a>

        </div>

    </form>

</section>


<!-- =========================================================
     JAVASCRIPT HARGA DINAMIS
     ========================================================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const pilihanKelas = document.getElementById('jadwal_kelas_id');

    const informasiHarga = document.getElementById('informasi-harga');

    const hargaCoach = document.getElementById('harga-coach');

    const hargaHari = document.getElementById('harga-hari');

    const hargaJam = document.getElementById('harga-jam');

    const hargaNominal = document.getElementById('harga-nominal');

    const hargaKuota = document.getElementById('harga-kuota');


    if (!pilihanKelas || !informasiHarga) {
        return;
    }


    function formatRupiah(nominal) {

        return 'Rp' + Number(nominal).toLocaleString('id-ID', {
            maximumFractionDigits: 0
        });

    }


    function tampilkanHarga() {

        const pilihan = pilihanKelas.selectedOptions[0];


        // Jika belum memilih kelas
        if (!pilihan || pilihan.value === '') {

            informasiHarga.style.display = 'none';

            hargaCoach.textContent = '-';

            hargaHari.textContent = '-';

            hargaJam.textContent = '-';

            hargaNominal.textContent = 'Rp0';

            hargaKuota.textContent =
                'Pilih kelas untuk melihat harga dan jadwal.';

            return;
        }


        // Ambil informasi dari kelas yang dipilih
        const harga = Number(pilihan.dataset.harga || 0);

        const coach = pilihan.dataset.coach || '-';

        const hari = pilihan.dataset.hari || '-';

        const jam = pilihan.dataset.jam || '-';

        const kuota = Number(pilihan.dataset.kuota || 0);


        // Tampilkan informasi kelas
        hargaCoach.textContent = coach;

        hargaHari.textContent = hari;

        hargaJam.textContent = jam + ' WIB';

        hargaNominal.textContent = formatRupiah(harga);


        // Informasi sisa kuota
        if (kuota > 0) {

            hargaKuota.textContent =
                'Sisa kuota tersedia: ' + kuota + ' orang';

            hargaKuota.style.color = '#6ee7b7';

        } else {

            hargaKuota.textContent = 'Kuota kelas sudah penuh';

            hargaKuota.style.color = '#fca5a5';

        }


        informasiHarga.style.display = 'block';

    }


    // Perbarui informasi setiap kali kelas dipilih
    pilihanKelas.addEventListener('change', tampilkanHarga);


    // Tampilkan informasi jika pilihan sudah terisi
    tampilkanHarga();

});
</script>


<?php include __DIR__ . '/../includes/footer.php'; ?>