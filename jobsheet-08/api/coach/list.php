<?php

session_start();

$page_title = "Jadwal Kelas Gym";

require_once __DIR__ . '/../includes/koneksi.php';

// Ambil kata kunci pencarian
$keyword = trim($_GET['search'] ?? '');

// Ambil data jadwal kelas
if ($keyword !== '') {
    $stmt = $pdo->prepare(
        "SELECT *
         FROM jadwal_kelas
         WHERE judul ILIKE :search
            OR pengarang ILIKE :search
            OR kategori ILIKE :search
            OR isbn ILIKE :search
         ORDER BY id DESC"
    );

    $stmt->execute([
        'search' => '%' . $keyword . '%'
    ]);

} else {
    $stmt = $pdo->query(
        "SELECT *
         FROM jadwal_kelas
         ORDER BY id DESC"
    );
}

$daftar_kelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

?>

<section>

    <?php if ($flash): ?>
        <div
            class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>"
            style="padding:12px; margin-bottom:20px;"
        >
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </div>
    <?php endif; ?>

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
            style="
                background:#e50914;
                color:#fff;
                padding:10px 18px;
                border-radius:4px;
                text-decoration:none;
                font-weight:bold;
            "
        >
            + Tambah Kelas
        </a>

    </div>

    <!-- Pencarian -->
    <form
        method="GET"
        action="list.php"
        style="
            margin-bottom:20px;
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        "
    >

        <input
            type="text"
            name="search"
            value="<?php echo htmlspecialchars($keyword); ?>"
            placeholder="Cari kelas, pelatih, hari, atau kode kelas..."
            style="
                flex:1;
                min-width:200px;
                padding:10px 12px;
                border-radius:4px;
                border:1px solid #444;
                background:#222;
                color:#fff;
            "
        >

        <button
            type="submit"
            style="
                background:#333;
                color:#fff;
                padding:10px 16px;
                border:1px solid #555;
                border-radius:4px;
                cursor:pointer;
            "
        >
            Cari
        </button>

        <?php if ($keyword !== ''): ?>
            <a
                href="list.php"
                style="
                    background:#555;
                    color:#fff;
                    padding:10px 16px;
                    border-radius:4px;
                    text-decoration:none;
                "
            >
                Reset
            </a>
        <?php endif; ?>

    </form>

    <!-- Tabel jadwal -->
    <div style="overflow-x:auto;">

        <table style="
            width:100%;
            border-collapse:collapse;
            background:#181818;
            color:#fff;
            text-align:left;
        ">

            <thead>
                <tr style="
                    background:#252525;
                    border-bottom:2px solid #333;
                ">
                    <th style="padding:12px;">No</th>
                    <th style="padding:12px;">Nama Kelas / Cabor</th>
                    <th style="padding:12px;">Coach / Pelatih</th>
                    <th style="padding:12px;">Hari Latihan</th>
                    <th style="padding:12px;">Jam Latihan</th>
                    <th style="padding:12px;">Kode / Level</th>
                    <th style="padding:12px;">Kuota</th>
                    <th style="padding:12px; text-align:center;">Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($daftar_kelas)): ?>

                    <?php foreach ($daftar_kelas as $index => $kelas): ?>

                        <tr style="border-bottom:1px solid #2a2a2a;">

                            <td style="padding:12px;">
                                <?php echo $index + 1; ?>
                            </td>

                            <td style="padding:12px; font-weight:bold;">
                                <?php echo htmlspecialchars($kelas['judul']); ?>
                            </td>

                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($kelas['pengarang']); ?>
                            </td>

                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($kelas['kategori'] ?? '-'); ?>
                            </td>

                            <td style="padding:12px;">
                                <?php
                                echo str_pad(
                                    (string) $kelas['tahun'],
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                );
                                ?>:00 WIB
                            </td>

                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($kelas['isbn'] ?? '-'); ?>
                            </td>

                            <td style="padding:12px;">
                                <?php echo htmlspecialchars((string) $kelas['stok']); ?>
                                Orang
                            </td>

                            <td style="padding:12px; text-align:center;">

                                <div style="
                                    display:flex;
                                    gap:8px;
                                    justify-content:center;
                                ">

                                    <a
                                        href="edit.php?id=<?php echo (int) $kelas['id']; ?>"
                                        style="
                                            background:#f39c12;
                                            color:#fff;
                                            padding:6px 14px;
                                            border-radius:4px;
                                            text-decoration:none;
                                            font-weight:bold;
                                        "
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="hapus.php"
                                        onsubmit="return confirm('Yakin ingin menghapus jadwal kelas ini?');"
                                        style="margin:0;"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo (int) $kelas['id']; ?>"
                                        >

                                        <button
                                            type="submit"
                                            style="
                                                background:#e50914;
                                                color:#fff;
                                                padding:6px 14px;
                                                border:none;
                                                border-radius:4px;
                                                cursor:pointer;
                                                font-weight:bold;
                                            "
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
                                padding:20px;
                                text-align:center;
                                color:#888;
                            "
                        >
                            Tidak ada jadwal kelas yang ditemukan.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>