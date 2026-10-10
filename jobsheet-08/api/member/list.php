<?php

session_start();

require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Daftar Member Gym';

// Kata kunci pencarian
$keyword = trim($_GET['search'] ?? '');

// Query daftar member beserta kelas yang diikuti
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
        j.jam_latihan,
        j.kode_kelas
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
        'hp'    => $term,
    ];
}

$sql .= ' ORDER BY a.id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$daftar_anggota = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil flash message satu kali
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Header
include __DIR__ . '/../includes/header.php';

?>

<section>

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

        <h2>DAFTAR MEMBER</h2>

        <a href="tambah.php" class="btn-primary">
            + Tambah Member
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
            placeholder="Cari nama, ID member, kelas, atau nomor HP..."
            aria-label="Cari member"
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


    <!-- TABEL MEMBER -->

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

                            <td>
                                <?php echo $index + 1; ?>
                            </td>

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

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $anggota['nama'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

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

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $anggota['nama_coach'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

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

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $anggota['nomor_hp'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>

                                <div style="
                                    display:flex;
                                    gap:8px;
                                    align-items:center;
                                    flex-wrap:wrap;
                                ">

                                    <!-- EDIT -->

                                    <a
                                        href="edit.php?id=<?php
                                            echo (int) $anggota['id'];
                                        ?>"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>


                                    <!-- HAPUS -->

                                    <form
                                        method="POST"
                                        action="hapus.php"
                                        style="margin:0;"
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

                                Data member untuk pencarian
                                "<?php
                                echo htmlspecialchars(
                                    $keyword,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>"
                                tidak ditemukan.

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

<?php include __DIR__ . '/../includes/footer.php'; ?>