<?php
$page_title = "Tambah Member";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>TAMBAH MEMBER</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </p>
    <?php endif; ?>

    <form id="form-tambah-anggota" method="post" action="proses_tambah.php">
        
        <p>
            <label for="nama">NAMA MEMBER</label><br>
            <input 
                type="text" 
                id="nama" 
                name="nama" 
                placeholder="Contoh: Daniel Ramadhani Zulkarnain" 
                required
            >
        </p>

        <p>
            <label for="nomor_anggota">ID MEMBER / NO. KARTU</label><br>
            <input 
                type="text" 
                id="nomor_anggota" 
                name="nomor_anggota" 
                placeholder="Contoh: A1" 
                required
            >
        </p>

        <!-- INPUT ALAMAT DIGANTI PILIHAN CABOR -->
        <p>
            <label for="cabor">CABOR / PILIHAN KELAS</label><br>
            <select id="cabor" name="cabor" style="width: 100%; padding: 10px; background: #1a1c23; color: white; border: 1px solid #333; border-radius: 4px;">
                <option value="Muay Thai Striking">Muay Thai Striking</option>
                <option value="Boxing">Boxing</option>
                <option value="Brazilian Jiu-Jitsu">Brazilian Jiu-Jitsu</option>
                <option value="Kickboxing">Kickboxing</option>
                <option value="Wrestling">Wrestling</option>
                <option value="MMA Conditioning">MMA Conditioning</option>
            </select>
        </p>

        <p>
            <label for="no_hp">NO. HP / WHATSAPP</label><br>
            <input 
                type="text" 
                id="no_hp" 
                name="no_hp" 
                placeholder="Contoh: 08123456789" 
                required
            >
        </p>

        <p>
            <button type="submit" class="btn-reload" style="background-color: #e50914; color: white; padding: 10px 24px; font-weight: bold; border: none; border-radius: 4px; cursor: pointer;">
                SIMPAN
            </button>
        </p>

    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>