<?php
$page_title = "Tambah Jadwal Kelas";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <!-- JUDUL FORM DISESUAIKAN DENGAN MENU NAVBAR: TAMBAH JADWAL KELAS -->
    <h2>TAMBAH JADWAL KELAS</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_tambah.php">
        
        <!-- 1. NAMA KELAS / CABOR (name="judul") -->
        <p>
            <label for="judul">NAMA KELAS / CABOR</label><br>
            <input 
                type="text" 
                id="judul" 
                name="judul" 
                placeholder="Contoh: Muay Thai Striking" 
                required
            >
        </p>

        <!-- 2. COACH / PELATIH (name="pengarang") -->
        <p>
            <label for="pengarang">COACH / PELATIH</label><br>
            <input 
                type="text" 
                id="pengarang" 
                name="pengarang" 
                placeholder="Contoh: Coach Alex" 
                required
            >
        </p>

        <!-- 3. JAM LATIHAN (name="tahun") -->
        <p>
            <label for="tahun">JAM LATIHAN (FORMAT 24 JAM)</label><br>
            <input 
                type="number" 
                id="tahun" 
                name="tahun" 
                min="1" 
                max="24" 
                placeholder="Contoh: 19" 
                required
            >
        </p>

        <!-- 4. KODE KELAS / LEVEL (name="isbn") -->
        <p>
            <label for="isbn">KODE KELAS / LEVEL</label><br>
            <input 
                type="text" 
                id="isbn" 
                name="isbn" 
                placeholder="Contoh: MT-01 / Beginner"
            >
        </p>

        <!-- 5. KUOTA MEMBER / SLOT (name="stok") -->
        <p>
            <label for="stok">KUOTA MEMBER (JUMLAH SLOT)</label><br>
            <input 
                type="number" 
                id="stok" 
                name="stok" 
                min="0" 
                placeholder="Contoh: 10" 
                required
            >
        </p>

        <!-- 6. HARI LATIHAN (name="kategori") -->
        <p>
            <label for="kategori">HARI LATIHAN</label><br>
            <select id="kategori" name="kategori">
                <option value="Senin & Rabu">Senin & Rabu</option>
                <option value="Selasa & Kamis">Selasa & Kamis</option>
                <option value="Jumat & Sabtu">Jumat & Sabtu</option>
                <option value="Sabtu & Minggu">Sabtu & Minggu</option>
                <option value="Setiap Hari">Setiap Hari</option>
            </select>
        </p>

        <!-- TOMBOL SIMPAN -->
        <p>
            <button type="submit" class="btn-reload" style="background-color: #e50914; color: white; padding: 10px 24px; font-weight: bold; border: none; border-radius: 4px; cursor: pointer;">
                SIMPAN
            </button>
        </p>

    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>