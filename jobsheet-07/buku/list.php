<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarBuku = $_SESSION['buku'] ?? [];
?> 

<section class="list-panel">
    <h2>Daftar Buku</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo $flash['pesan']; ?>
        </p>
    <?php endif; ?>

    <div class="search-box">
        <div class="search-controls">
            <label for="search-input">Cari Judul Buku</label>

            <input
                type="text"
                id="search-input"
                placeholder="Ketik judul buku..."
            >

            <button type="button" id="reload-btn">
                Muat Ulang
            </button>
        </div>
    </div>

    <p class="table-count">Total buku: <strong><?php echo count($daftarBuku); ?></strong></p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="5">
                            Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".
                        </td>
                    </tr>
                <?php else: ?>

                    <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo htmlspecialchars((string) ($buku['judul'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string) ($buku['pengarang'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string) ($buku['tahun'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string) ($buku['stok'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>