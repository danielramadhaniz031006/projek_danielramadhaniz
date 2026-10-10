<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Edit Member';


// =========================================================
// 1. VALIDASI ID MEMBER
// =========================================================

$id = $_GET['id'] ?? '';

if (
    !ctype_digit((string) $id)
    || (int) $id < 1
) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID member tidak valid.',
    ];

    header('Location: list.php');

    exit;
}


// =========================================================
// 2. AMBIL DATA MEMBER
// =========================================================

$stmt = $pdo->prepare("
    SELECT
        a.id,
        a.nomor_member,
        a.nama,
        a.jadwal_kelas_id,
        a.level,
        a.nomor_hp,
        j.nama_kelas

    FROM public.anggota a

    LEFT JOIN public.jadwal_kelas j
        ON j.id = a.jadwal_kelas_id

    WHERE a.id = :id
");

$stmt->execute([
    'id' => (int) $id,
]);

$anggota = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$anggota) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data member tidak ditemukan.',
    ];

    header('Location: list.php');

    exit;
}


$error = '';


// =========================================================
// 3. PROSES UPDATE MEMBER
// =========================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nomorMember = trim(
        $_POST['nomor_member']
        ?? $_POST['no_anggota']
        ?? ''
    );

    $nama = trim(
        $_POST['nama'] ?? ''
    );

    $jadwalKelasId = trim(
        (string) ($_POST['jadwal_kelas_id'] ?? '')
    );

    $level = trim(
        $_POST['level'] ?? 'Pemula (Beginner)'
    );

    $nomorHp = trim(
        $_POST['nomor_hp']
        ?? $_POST['no_hp']
        ?? ''
    );


    // Validasi
    if ($nomorMember === '') {

        $error = 'Nomor member wajib diisi.';

    } elseif (mb_strlen($nomorMember) > 20) {

        $error = 'Nomor member maksimal 20 karakter.';

    } elseif ($nama === '') {

        $error = 'Nama member wajib diisi.';

    } elseif (mb_strlen($nama) > 100) {

        $error = 'Nama member maksimal 100 karakter.';

    } elseif (
        !ctype_digit($jadwalKelasId)
        || (int) $jadwalKelasId < 1
    ) {

        $error = 'Silakan pilih kelas yang valid.';

    } elseif (mb_strlen($nomorHp) > 20) {

        $error = 'Nomor HP maksimal 20 karakter.';
    }


    // Validasi level
    $levelValid = [
        'Pemula (Beginner)',
        'Menengah (Intermediate)',
        'Mahir (Advanced)',
    ];

    if (!in_array($level, $levelValid, true)) {
        $level = 'Pemula (Beginner)';
    }


    // =====================================================
    // UPDATE DATABASE
    // =====================================================

    if ($error === '') {

        try {

            $pdo->beginTransaction();


            // Kunci kelas yang dipilih
            $stmtKelas = $pdo->prepare("
                SELECT
                    id,
                    nama_kelas,
                    kapasitas
                FROM public.jadwal_kelas
                WHERE id = :id
                FOR UPDATE
            ");

            $stmtKelas->execute([
                'id' => (int) $jadwalKelasId,
            ]);

            $kelasTujuan = $stmtKelas->fetch(
                PDO::FETCH_ASSOC
            );


            // Pastikan kelas ditemukan
            if (!$kelasTujuan) {

                $pdo->rollBack();

                $error = 'Kelas yang dipilih tidak ditemukan.';

            } else {

                // Apakah member pindah kelas?
                $kelasBerubah = (
                    (int) $jadwalKelasId
                    !== (int) $anggota['jadwal_kelas_id']
                );


                // Periksa kuota hanya ketika pindah kelas
                if ($kelasBerubah) {

                    $stmtJumlah = $pdo->prepare("
                        SELECT COUNT(*)
                        FROM public.anggota
                        WHERE jadwal_kelas_id = :id
                    ");

                    $stmtJumlah->execute([
                        'id' => (int) $jadwalKelasId,
                    ]);

                    $jumlahMember = (int)
                        $stmtJumlah->fetchColumn();


                    if (
                        $jumlahMember
                        >= (int) $kelasTujuan['kapasitas']
                    ) {

                        $pdo->rollBack();

                        $error = 'Kuota kelas '
                            . $kelasTujuan['nama_kelas']
                            . ' sudah penuh.';
                    }
                }


                // Simpan perubahan jika valid
                if ($error === '') {

                    $stmtUpdate = $pdo->prepare("
                        UPDATE public.anggota

                        SET
                            nomor_member = :nomor_member,
                            nama = :nama,
                            jadwal_kelas_id = :jadwal_kelas_id,
                            level = :level,
                            nomor_hp = :nomor_hp

                        WHERE id = :id
                    ");

                    $stmtUpdate->execute([
                        'nomor_member' => $nomorMember,
                        'nama' => $nama,
                        'jadwal_kelas_id' => (int) $jadwalKelasId,
                        'level' => $level,
                        'nomor_hp' => $nomorHp !== ''
                            ? $nomorHp
                            : null,
                        'id' => (int) $id,
                    ]);


                    $pdo->commit();


                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'pesan' => 'Data member berhasil diperbarui.',
                    ];

                    header('Location: list.php');

                    exit;
                }
            }


        } catch (PDOException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if ($e->getCode() === '23505') {

                $error = 'Nomor member sudah digunakan. '
                    . 'Gunakan nomor lain.';

            } else {

                error_log(
                    'Gagal mengubah member: '
                    . $e->getMessage()
                );

                $error = 'Gagal memperbarui data member. '
                    . 'Periksa konfigurasi database.';
            }
        }
    }


    // Pertahankan input saat terjadi kesalahan
    $anggota['nomor_member'] = $nomorMember;
    $anggota['nama'] = $nama;
    $anggota['jadwal_kelas_id'] = $jadwalKelasId;
    $anggota['level'] = $level;
    $anggota['nomor_hp'] = $nomorHp;
}


// =========================================================
// 4. AMBIL PILIHAN KELAS DAN SISA KUOTA
// =========================================================

$stmtKelas = $pdo->query("
    SELECT
        j.id,
        j.nama_kelas,
        j.nama_coach,
        j.hari_latihan,
        j.jam_latihan,
        j.kapasitas,

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
// 5. HEADER
// =========================================================

include __DIR__ . '/../includes/header.php';

?>


<section style="
    max-width:700px;
    margin:0 auto;
    padding:20px;
">

    <h2>EDIT DATA MEMBER</h2>


    <?php if ($error !== ''): ?>

        <div class="flash flash-error">

            <?php
            echo htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            );
            ?>

        </div>

    <?php endif; ?>


    <form
        method="POST"
        action="edit.php?id=<?php echo (int) $id; ?>"
        style="
            max-width:100%;
            margin-top:20px;
        "
    >


        <!-- NOMOR MEMBER -->

        <div style="margin-bottom:18px;">

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
                        (string) $anggota['nomor_member'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?>"
                required
            >

        </div>


        <!-- NAMA MEMBER -->

        <div style="margin-bottom:18px;">

            <label for="nama">
                Nama Member
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                value="<?php
                    echo htmlspecialchars(
                        (string) $anggota['nama'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?>"
                required
            >

        </div>


        <!-- PILIHAN KELAS -->

        <div style="margin-bottom:18px;">

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

                    $selected = (
                        (int) $kelas['id']
                        === (int) $anggota['jadwal_kelas_id']
                    );

                    $sisa = (int) $kelas['sisa_kuota'];

                    $disabled = (
                        $sisa <= 0
                        && !$selected
                    );

                    ?>


                    <option
                        value="<?php echo (int) $kelas['id']; ?>"
                        <?php echo $selected ? 'selected' : ''; ?>
                        <?php echo $disabled ? 'disabled' : ''; ?>
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

        </div>


        <!-- LEVEL MEMBER -->

        <div style="margin-bottom:18px;">

            <label for="level">
                Level Member
            </label>

            <select
                id="level"
                name="level"
            >

                <?php

                $levels = [
                    'Pemula (Beginner)',
                    'Menengah (Intermediate)',
                    'Mahir (Advanced)',
                ];

                ?>


                <?php foreach ($levels as $levelOption): ?>

                    <option
                        value="<?php
                            echo htmlspecialchars(
                                $levelOption,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                        ?>"
                        <?php
                        echo (
                            ($anggota['level']
                                ?? 'Pemula (Beginner)')
                            === $levelOption
                        ) ? 'selected' : '';
                        ?>
                    >

                        <?php
                        echo htmlspecialchars(
                            $levelOption,
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- NOMOR HP -->

        <div style="margin-bottom:18px;">

            <label for="nomor_hp">
                No. HP / WhatsApp
            </label>

            <input
                type="tel"
                id="nomor_hp"
                name="nomor_hp"
                maxlength="20"
                value="<?php
                    echo htmlspecialchars(
                        (string) (
                            $anggota['nomor_hp'] ?? ''
                        ),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?>"
            >

        </div>


        <!-- TOMBOL -->

        <button type="submit">
            SIMPAN PERUBAHAN
        </button>

        <a
            href="list.php"
            class="btn-secondary"
            style="margin-left:10px;"
        >
            BATAL
        </a>

    </form>

</section>


<?php include __DIR__ . '/../includes/footer.php'; ?>s