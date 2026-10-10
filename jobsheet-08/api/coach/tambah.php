<?php
session_start();

$page_title = "Tambah Jadwal Kelas";

include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>TAMBAH JADWAL KELAS</h2>

    <?php if ($flash): ?>
        <div class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="proses_tambah.php"
          style="max-width: 600px; margin-top: 20px;">

        <div style="margin-bottom: 18px;">
            <label for="judul">Nama Kelas / Cabor</label>

            <input
                type="text"
                id="judul"
                name="judul"
                placeholder="Contoh: Muay Thai Striking"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >
        </div>

        <div style="margin-bottom: 18px;">
            <label for="pengarang">Coach / Pelatih</label>

            <input
                type="text"
                id="pengarang"
                name="pengarang"
                placeholder="Contoh: Coach Alex"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >
        </div>

        <div style="margin-bottom: 18px;">
            <label for="kategori">Hari Latihan</label>

            <select
                id="kategori"
                name="kategori"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >
                <option value="Senin & Rabu">Senin & Rabu</option>
                <option value="Selasa & Kamis">Selasa & Kamis</option>
                <option value="Jumat & Sabtu">Jumat & Sabtu</option>
                <option value="Sabtu & Minggu">Sabtu & Minggu</option>
                <option value="Setiap Hari">Setiap Hari</option>
            </select>
        </div>

        <div style="margin-bottom: 18px;">
            <label for="tahun">Jam Latihan</label>

            <input
                type="number"
                id="tahun"
                name="tahun"
                min="0"
                max="23"
                placeholder="Contoh: 19"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >

            <small>Masukkan jam dalam format 24 jam, misalnya 19 untuk pukul 19.00 WIB.</small>
        </div>

        <div style="margin-bottom: 18px;">
            <label for="isbn">Kode Kelas / Level</label>

            <input
                type="text"
                id="isbn"
                name="isbn"
                placeholder="Contoh: MT-01 / Beginner"
                style="width:100%; padding:10px; margin-top:8px;"
            >
        </div>

        <div style="margin-bottom: 18px;">
            <label for="stok">Kuota Member</label>

            <input
                type="number"
                id="stok"
                name="stok"
                min="0"
                placeholder="Contoh: 10"
                required
                style="width:100%; padding:10px; margin-top:8px;"
            >
        </div>

        <button
            type="submit"
            style="background:#e50914; color:white;
                   padding:10px 24px; font-weight:bold;
                   border:none; border-radius:4px; cursor:pointer;"
        >
            SIMPAN JADWAL
        </button>

        <a
            href="list.php"
            style="margin-left:10px; text-decoration:none;"
        >
            Batal
        </a>

    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>