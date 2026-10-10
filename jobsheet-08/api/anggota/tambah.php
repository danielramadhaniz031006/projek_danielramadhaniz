<!-- CABOR / PILIHAN KELAS -->
<div style="margin-bottom: 20px;">

    <label style="display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; color: #fff;">
        CABOR / PILIHAN KELAS
    </label>

    <?php
    $stmtPilihan = $pdo->query(
        "SELECT judul, stok
         FROM buku
         ORDER BY judul ASC"
    );

    $pilihanKelas = $stmtPilihan->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <select
        name="cabor"
        required
        style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #333; background: #222; color: #fff; box-sizing: border-box;"
    >

        <option value="">-- Pilih Kelas --</option>

        <?php foreach ($pilihanKelas as $kelas): ?>

            <option
                value="<?php echo htmlspecialchars($kelas['judul']); ?>"
                <?php echo ((int)$kelas['stok'] <= 0) ? 'disabled' : ''; ?>
            >
                <?php
                echo htmlspecialchars($kelas['judul']);
                ?>
                (Sisa kuota: <?php echo (int)$kelas['stok']; ?>)
            </option>

        <?php endforeach; ?>

    </select>

</div>