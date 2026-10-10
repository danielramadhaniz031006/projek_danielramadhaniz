<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Tambah Member';


// =========================================================
// 1. GENERATE NOMOR MEMBER OTOMATIS
// =========================================================

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


// =========================================================
// 2. AMBIL DAFTAR KELAS DAN SISA KUOTA
// =========================================================

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


// =========================================================
// 3. NOTIFIKASI
// =========================================================

$flash = $_SESSION['flash'] ?? null;

unset($_SESSION['flash']);


// =========================================================
// 4. HEADER
// =========================================================

include __DIR__ . '/../includes/header.php';

?>


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


    <!-- FORM TAMBAH MEMBER -->

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


        <!-- NAMA MEMBER -->

        <div style="margin-bottom:20px;">

            <label for="nama">
                Nama Member
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


        <!-- NOMOR MEMBER -->

        <div style="margin-bottom:20px;">

            <label for="nomor_member">
                ID Member / Nomor Kartu
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

            <small>
                Nomor member disarankan otomatis dan bisa diubah.
            </small>

        </div>


        <!-- PILIHAN KELAS -->

        <div style="margin-bottom:20px;">

            <label for="jadwal_kelas_id">
                Cabor / Pilihan Kelas
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

                    $sisa = (int) $kelas['sisa_kuota'];

                    ?>

                    <option
                        value="<?php echo (int) $kelas['id']; ?>"
                        <?php echo $sisa <= 0 ? 'disabled' : ''; ?>
                    >

                        <?php

                        echo htmlspecialchars(
                            $kelas['nama_kelas'],
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        echo ' — ';

                        echo htmlspecialchars(
                            $kelas['nama_coach'],
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        echo ' — ';

                        echo htmlspecialchars(
                            $kelas['hari_latihan'],
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        echo ' ';

                        echo htmlspecialchars(
                            $jam,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        echo ' — Sisa kuota: ' . $sisa;

                        ?>

                    </option>

                <?php endforeach; ?>

            </select>


            <?php if (empty($pilihanKelas)): ?>

                <small style="
                    display:block;
                    margin-top:8px;
                    color:#fca5a5;
                ">
                    Belum ada jadwal kelas.
                    Tambahkan jadwal kelas terlebih dahulu.
                </small>

            <?php endif; ?>

        </div>


        <!-- LEVEL MEMBER -->

        <div style="margin-bottom:20px;">

            <label for="level">
                Level Member
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


        <!-- NOMOR HP -->

        <div style="margin-bottom:20px;">

            <label for="nomor_hp">
                No. HP / WhatsApp
            </label>

            <input
                type="tel"
                id="nomor_hp"
                name="nomor_hp"
                maxlength="20"
                placeholder="Contoh: 08123456789"
            >

        </div>


        <!-- TOMBOL -->

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


<?php include __DIR__ . '/../includes/footer.php'; ?>