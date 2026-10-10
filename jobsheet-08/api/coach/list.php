<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Daftar Jadwal Kelas';

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
    ";

    $term = '%' . $keyword . '%';

    $params = [
        'cari_kelas' => $term,
        'cari_coach' => $term,
        'cari_hari'  => $term
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

        <a href="tambah.php" class="btn-primary">
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
            placeholder="Cari kelas, coach, atau hari latihan..."
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


    <!-- =====================================================
         TABEL JADWAL KELAS
         KODE / LEVEL SUDAH DIHAPUS
         ===================================================== -->

    <div class="table-responsive">

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Nama Kelas / Cabor</th>
                    <th>Coach / Pelatih</th>
                    <th>Hari Latihan</th>
                    <th>Jam Latihan</th>
                    <th>Kuota</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                <?php if (!empty($daftar_kelas)): ?>

                    <?php foreach ($daftar_kelas as $index => $kelas): ?>

                        <?php
                        $jam = substr(
                            (string) $kelas['jam_latihan'],
                            0,
                            5
                        );

                        $sisaKuota = (int) $kelas['sisa_kuota'];

                        $kapasitas = (int) $kelas['kapasitas'];
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
                                        $kelas['nama_kelas'],
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
                                    $kelas['nama_coach'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>


                            <!-- HARI LATIHAN -->

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $kelas['hari_latihan'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>


                            <!-- JAM LATIHAN -->

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $jam,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?> WIB
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
                            colspan="8"
                            style="
                                padding:25px;
                                text-align:center;
                                color:#9ca3af;
                            "
                        >

                            <?php if ($keyword !== ''): ?>

                                Jadwal kelas tidak ditemukan.

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


<?php include __DIR__ . '/../includes/footer.php'; ?>