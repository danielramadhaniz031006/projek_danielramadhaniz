<?php

$page_title = "Jadwal Kelas Training Camp";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['keyword'] ?? '');

$stmt = $pdo->prepare(
    "SELECT *
     FROM buku
     WHERE judul ILIKE :keyword
     ORDER BY id DESC"
);

$stmt->execute([
    'keyword' => '%' . $keyword . '%'
]);

$jadwalKelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
    /* Custom Styling untuk Merapikan Tabel Jadwal Cabor */
    .table-responsive table {
        width: 100%;
        border-collapse: collapse;
    }
    .table-responsive th, 
    .table-responsive td {
        vertical-align: middle !important;
        padding: 12px 14px;
    }
    .badge-status {
        display: inline-block;
        white-space: nowrap;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
        text-align: center;
    }
    .badge-open {
        background: rgba(46, 196, 182, 0.15);
        color: #2ec4b6;
        border: 1px solid #2ec4b6;
    }
    .badge-full {
        background: rgba(255, 77, 77, 0.15);
        color: #ff4d4d;
        border: 1px solid #ff4d4d;
    }
    .badge-schedule {
        background: #1e1e1e;
        border: 1px solid #333;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #ffc107;
        display: inline-block;
        white-space: nowrap;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }
</style>

<section>

    <!-- HEADER & TOMBOL TAMBAH KELAS -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
        <h2 style="margin: 0;">Jadwal Kelas Training Camp</h2>
        <a href="tambah.php" class="btn-reload" style="background-color: #e50914; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">
            + Tambah Kelas
        </a>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo $flash['pesan']; ?>
        </p>
    <?php endif; ?>

    <!-- SEARCH BOX -->
    <div class="search-box">
        <form method="GET" action="list.php">
            <label for="search-input">
                Cari Jadwal Kelas
            </label>

            <input
                type="text"
                id="search-input"
                name="keyword"
                value="<?php echo htmlspecialchars($keyword); ?>"
                placeholder="Ketik cabor (Muay Thai, Boxing, BJJ...)"
            >

            <button type="submit" class="btn-reload">
                Cari
            </button>

            <button type="button" class="btn-reload" onclick="location.href='list.php'">
                Muat Ulang
            </button>
        </form>
    </div>

    <!-- TOTAL KELAS INFO -->
    <p style="margin: 14px 0 8px; color: #aaa; font-size: 0.88rem;">
        Total Kelas Tersedia: <strong><?php echo count($jadwalKelas); ?> Sesi Latihan</strong>
    </p>

    <!-- TABEL JADWAL KELAS -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="text-align: left;">Nama Kelas / Cabor</th>
                    <th style="text-align: left;">Coach / Pelatih</th>
                    <th style="text-align: center;">Hari & Jam Latihan</th>
                    <th style="text-align: center;">Sisa Kuota</th>
                    <th style="text-align: center;">Status Kelas</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jadwalKelas)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #888; padding: 24px;">
                            <?php if ($keyword !== ''): ?>
                                Jadwal kelas dengan pencarian "<?php echo htmlspecialchars($keyword); ?>" tidak ditemukan.
                            <?php else: ?>
                                Belum ada jadwal kelas. Silakan klik "+ Tambah Kelas".
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($jadwalKelas as $kelas): ?>
                        <tr>
                            <!-- 1. NAMA KELAS -->
                            <td style="text-align: left;">
                                <strong style="color: #fff; font-size: 0.98rem;">
                                    <?php echo htmlspecialchars($kelas['judul']); ?>
                                </strong>
                            </td>

                            <!-- 2. COACH -->
                            <td style="text-align: left; color: #ddd;">
                                🥊 <?php echo htmlspecialchars($kelas['pengarang']); ?>
                            </td>

                            <!-- 3. HARI & JAM LATIHAN -->
                            <td style="text-align: center;">
                                <span class="badge-schedule">
                                    <?php 
                                        $hari = !empty($kelas['kategori']) ? $kelas['kategori'] : 'Jadwal Reguler';
                                        $jam = ($kelas['tahun'] > 0 && $kelas['tahun'] <= 24) ? sprintf("%02d:00 WIB", $kelas['tahun']) : 'Sesi Malam';
                                        echo htmlspecialchars($hari) . ' (' . htmlspecialchars($jam) . ')';
                                    ?>
                                </span>
                            </td>

                            <!-- 4. SISA KUOTA -->
                            <td style="text-align: center; font-weight: bold; color: #fff;">
                                <?php echo htmlspecialchars($kelas['stok']); ?> Orang
                            </td>

                            <!-- 5. STATUS KELAS (SATU BARIS RAPI) -->
                            <td style="text-align: center;">
                                <?php if ($kelas['stok'] > 0): ?>
                                    <span class="badge-status badge-open">
                                        ● Pendaftaran Dibuka
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status badge-full">
                                        ● Kelas Penuh
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- 6. AKSI EDIT / HAPUS -->
                            <td style="text-align: center;">
                                <div class="action-buttons">
                                    <button type="button" style="margin: 0; padding: 6px 14px;">Edit</button>
                                    <button type="button" class="btn-hapus" style="margin: 0; padding: 6px 14px;">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</section>

<?php
include __DIR__ . '/../includes/footer.php';
?>