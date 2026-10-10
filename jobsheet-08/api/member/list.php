<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';


/* =========================================================
   ELRAM TRAINING CAMP
   DAFTAR MEMBER
   ========================================================= */


/* =========================================================
   1. TOKEN KEAMANAN UNTUK RESET
   ========================================================= */

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


/* =========================================================
   2. JUDUL HALAMAN
   ========================================================= */

$page_title = 'Daftar Member Gym';


/* =========================================================
   3. PENCARIAN MEMBER
   ========================================================= */

$keyword = trim($_GET['search'] ?? '');

$sql = "
    SELECT
        a.id,
        a.nomor_member,
        a.nama,
        a.level,
        a.nomor_hp,
        j.nama_kelas,
        j.nama_coach,
        j.hari_latihan,
        j.jam_latihan

    FROM public.anggota a

    LEFT JOIN public.jadwal_kelas j
        ON j.id = a.jadwal_kelas_id
";

$params = [];

if ($keyword !== '') {

    $sql .= "
        WHERE a.nama ILIKE :nama
           OR a.nomor_member ILIKE :nomor
           OR COALESCE(j.nama_kelas, '') ILIKE :kelas
           OR COALESCE(a.nomor_hp, '') ILIKE :hp
    ";

    $term = '%' . $keyword . '%';

    $params = [
        'nama'  => $term,
        'nomor' => $term,
        'kelas' => $term,
        'hp'    => $term
    ];
}

$sql .= ' ORDER BY a.id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$daftar_anggota = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================================================
   4. JUMLAH MEMBER
   ========================================================= */

$totalMember = count($daftar_anggota);


/* =========================================================
   5. NOTIFIKASI
   ========================================================= */

$flash = $_SESSION['flash'] ?? null;

unset($_SESSION['flash']);


/* =========================================================
   6. HEADER
   ========================================================= */

include __DIR__ . '/../includes/header.php';

?>


<!-- =========================================================
     DAFTAR MEMBER
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


    <!-- =====================================================
         JUDUL DAN TOMBOL AKSI
         ===================================================== -->

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:12px;
        margin-bottom:20px;
    ">

        <div>

            <h2>DAFTAR MEMBER</h2>

            <p style="
                color:#9ca3af;
                font-size:.85rem;
                margin-top:8px;
            ">

                Total member:
                <strong style="color:#fff;">
                    <?php echo $totalMember; ?>
                </strong>

                <?php if ($keyword !== ''): ?>

                    <span>hasil pencarian</span>

                <?php endif; ?>

            </p>

        </div>


        <div style="
            display:flex;
            align-items:center;
            flex-wrap:wrap;
            gap:10px;
        ">


            <!-- TOMBOL RESET MINGGUAN -->

            <form
                method="POST"
                action="reset_mingguan.php"
                style="
                    width:auto;
                    max-width:none;
                    margin:0;
                "
                onsubmit="return confirm(
                    'PERINGATAN! Semua data member akan dihapus. ' +
                    'Jadwal kelas, coach, dan harga tidak akan dihapus. ' +
                    'Yakin ingin melanjutkan reset mingguan?'
                );"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?php
                        echo htmlspecialchars(
                            $_SESSION['csrf_token'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    ?>"
                >

                <button
                    type="submit"
                    style="
                        background:#991b1b;
                        color:#fff;
                        padding:10px 16px;
                        border:1px solid #dc2626;
                        border-radius:6px;
                        font-weight:bold;
                        cursor:pointer;
                        white-space:nowrap;
                    "
                >

                    RESET MINGGUAN

                </button>

            </form>


            <!-- TOMBOL TAMBAH MEMBER -->

            <a
                href="tambah.php"
                class="btn-primary"
            >

                + Tambah Member

            </a>

        </div>

    </div>


    <!-- =====================================================
         FORM PENCARIAN
         ===================================================== -->

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
            placeholder="Cari nama, ID member, kelas, atau nomor HP..."
            aria-label="Cari member"
        >

        <button type="submit">
            CARI
        </button>

        <?php if ($keyword !== ''): ?>

            <a href="list.php">
                RESET PENCARIAN
            </a>

        <?php endif; ?>

    </form>


    <!-- =====================================================
         TABEL MEMBER
         ===================================================== -->

    <div class="table-responsive">

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>ID Member</th>

                    <th>Nama Member</th>

                    <th>Kelas</th>

                    <th>Coach</th>

                    <th>Level</th>

                    <th>No. HP / WA</th>

                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

                <?php if (!empty($daftar_anggota)): ?>

                    <?php foreach (
                        $daftar_anggota as $index => $anggota
                    ): ?>

                        <tr>

                            <!-- NOMOR -->

                            <td>
                                <?php echo $index + 1; ?>
                            </td>


                            <!-- ID MEMBER -->

                            <td>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $anggota['nomor_member'] ?? '-',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>

                                </strong>

                            </td>


                            <!-- NAMA MEMBER -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $anggota['nama'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            </td>


                            <!-- KELAS -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $anggota['nama_kelas']
                                        ?? 'Kelas tidak tersedia',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            </td>


                            <!-- COACH -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $anggota['nama_coach'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            </td>


                            <!-- LEVEL -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $anggota['level']
                                        ?? 'Pemula (Beginner)',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            </td>


                            <!-- NOMOR HP -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $anggota['nomor_hp'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            </td>


                            <!-- AKSI -->

                            <td>

                                <div style="
                                    display:flex;
                                    align-items:center;
                                    justify-content:flex-start;
                                    flex-direction:row;
                                    flex-wrap:nowrap;
                                    gap:8px;
                                ">


                                    <!-- EDIT -->

                                    <a
                                        href="edit.php?id=<?php
                                            echo (int) $anggota['id'];
                                        ?>"
                                        class="btn-edit"
                                    >

                                        EDIT

                                    </a>


                                    <!-- HAPUS SATU MEMBER -->

                                    <form
                                        method="POST"
                                        action="hapus.php"
                                        style="
                                            width:auto;
                                            max-width:none;
                                            margin:0;
                                        "
                                        onsubmit="return confirm(
                                            'Yakin ingin menghapus member ini?'
                                        );"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php
                                                echo (int) $anggota['id'];
                                            ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn-danger"
                                        >

                                            HAPUS

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

                                Member tidak ditemukan dengan kata kunci
                                "<?php
                                echo htmlspecialchars(
                                    $keyword,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>".

                            <?php else: ?>

                                Belum ada data member.

                                Silakan tambahkan member terlebih dahulu.

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
   7. FOOTER
   ========================================================= */

include __DIR__ . '/../includes/footer.php';

?>