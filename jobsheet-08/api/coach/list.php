<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

/* =========================================================
   ELRAM TRAINING CAMP
   DAFTAR JADWAL KELAS
   ========================================================= */

$page_title = "Daftar Jadwal Kelas";

/* =========================================================
   1. PENCARIAN JADWAL KELAS
   ========================================================= */

$keyword = trim($_GET['search'] ?? '');

$sql = "
    SELECT
        j.id,
        j.nama_kelas,
        j.nama_coach,
        j.hari_latihan,
        j.jam_latihan,
        j.kode_kelas,
        j.kapasitas,
        j.harga,

        COUNT(a.id) AS member_terdaftar,

        GREATEST(
            j.kapasitas - COUNT(a.id),
            0
        ) AS sisa_kuota

    FROM public.jadwal_kelas j

    LEFT JOIN public.anggota a
        ON a.jadwal_kelas_id = j.id
";

$params = [];

if ($keyword !== '') {

    $sql .= "
        WHERE j.nama_kelas ILIKE :cari_kelas
           OR j.nama_coach ILIKE :cari_coach
           OR j.hari_latihan ILIKE :cari_hari
           OR j.kode_kelas ILIKE :cari_kode
    ";

    $cari = '%' . $keyword . '%';

    $params = [
        'cari_kelas' => $cari,
        'cari_coach' => $cari,
        'cari_hari'  => $cari,
        'cari_kode'  => $cari
    ];
}

$sql .= "
    GROUP BY j.id
    ORDER BY j.id DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$daftar_kelas = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================================================
   2. NOTIFIKASI
   ========================================================= */

$flash = $_SESSION['flash'] ?? null;

unset($_SESSION['flash']);


/* =========================================================
   3. HEADER
   ========================================================= */

include __DIR__ . '/../includes/header.php';

?>


<!-- =========================================================
     DAFTAR JADWAL KELAS
     ========================================================= -->

<section>

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


    <!-- JUDUL DAN TOMBOL TAMBAH -->

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:12px;
        margin-bottom:20px;
    ">

        <h2>DAFTAR JADWAL KELAS</h2>

        <a
            href="tambah.php"
            class="btn-primary"
        >
            + Tambah Kelas
        </a>

    </div>


    <!-- PENCARIAN -->

    <form
        method="GET"
        action="list.php"
        class="search-form"
    >

        <input
            type="text"
            name="search"
            value="<?php
                echo htmlspecialchars(
                    $keyword,
                    ENT_QUOTES,
                    'UTF-8'
                );
            ?>"
            placeholder="Cari kelas, coach, hari, atau kode kelas..."
            aria-label="Cari jadwal kelas"
        >

        <button type="submit">
            CARI
        </button>

        <?php if ($keyword !== ''): ?>

            <a href="list.php">
                RESET
            </a>

        <?php endif; ?>

    </form>


    <!-- TABEL JADWAL -->

    <div class="table-responsive">

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Nama Kelas / Cabor</th>
                    <th>Coach / Pelatih</th>
                    <th>Hari Latihan</th>
                    <th>Jam Latihan</th>
                    <th>Kode / Level</th>
                    <th>Kuota</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                <?php if (!empty($daftar_kelas)): ?>

                    <?php foreach ($daftar_kelas as $index => $kelas): ?>

                        <?php

                        $jam = (string) (
                            $kelas['jam_latihan'] ?? ''
                        );

                        $jamTampil = substr($jam, 0, 5);

                        $sisaKuota = (int) (
                            $kelas['sisa_kuota'] ?? 0
                        );

                        $kapasitas = (int) (
                            $kelas['kapasitas'] ?? 0
                        );

                        ?>

                        <tr>

                            <!-- NOMOR -->

                            <td>
                                <?php echo $index + 1; ?>
                            </td>


                            <!-- NAMA KELAS -->

                            <td>
                                <strong>
                                    <?php
                                    echo htmlspecialchars(
                                        $kelas['nama_kelas'] ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </strong>
                            </td>


                            <!-- COACH -->

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $kelas['nama_coach'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>


                            <!-- HARI -->

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $kelas['hari_latihan'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>


                            <!-- JAM -->

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $jamTampil,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?> WIB
                            </td>


                            <!-- KODE KELAS -->

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $kelas['kode_kelas'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>


                            <!-- SISA KUOTA -->

                            <td>

                                <?php echo $sisaKuota; ?>

                                /
                                <?php echo $kapasitas; ?>

                                Orang

                            </td>


                            <!-- HARGA -->

                            <td>
                                Rp<?php
                                echo number_format(
                                    (float) $kelas['harga'],
                                    0,
                                    ',',
                                    '.'
                                );
                                ?>
                            </td>


                            <!-- AKSI -->

                            <td>

                                <div style="
                                    display:flex;
                                    gap:8px;
                                    align-items:center;
                                ">

                                    <a
                                        href="edit.php?id=<?php
                                            echo (int) $kelas['id'];
                                        ?>"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="hapus.php"
                                        style="margin:0;"
                                        onsubmit="return confirm(
                                            'Yakin ingin menghapus jadwal kelas ini?'
                                        );"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php
                                                echo (int) $kelas['id'];
                                            ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn-danger"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="9"
                            style="
                                padding:25px;
                                text-align:center;
                                color:#9ca3af;
                            "
                        >

                            <?php if ($keyword !== ''): ?>

                                Jadwal kelas dengan kata kunci
                                "<?php
                                echo htmlspecialchars(
                                    $keyword,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                                tidak ditemukan.

                            <?php else: ?>

                                Belum ada jadwal kelas.
                                Silakan tambahkan kelas terlebih dahulu.

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>


<?php

/* =========================================================
   4. FOOTER
   ========================================================= */

include __DIR__ . '/../includes/footer.php';

?>