<?php
require_once __DIR__ . '/includes/koneksi.php';

$totalKelas = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
$totalCoach = $pdo->query("SELECT COUNT(DISTINCT pengarang) FROM buku")->fetchColumn();
$stokBuku = $pdo->query("SELECT COALESCE(SUM(stok), 0) FROM buku")->fetchColumn();
$totalKapasitas = ($stokBuku > $totalAnggota) ? (int)$stokBuku : 70;
$totalKuota = max(0, $totalKapasitas - (int)$totalAnggota);

$stmtCoach = $pdo->query("SELECT pengarang AS nama_coach, STRING_AGG(judul, ', ') AS daftar_cabor, COUNT(*) AS total_kelas FROM buku GROUP BY pengarang ORDER BY pengarang ASC");
$daftarCoach = $stmtCoach->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Beranda Premium";
include __DIR__ . '/includes/header.php';
?>
<style>
    .bento-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 20px; margin-bottom: 30px; }
    .bento-welcome { grid-column: span 8; background: linear-gradient(135deg, #181a20 0%, #0e0f13 100%); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 35px; }
    .bento-stats { grid-column: span 4; background: rgba(22, 24, 30, 0.5); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 25px; }
    .stats-inner-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
    .stat-box { background: rgba(9, 10, 13, 0.5); border-radius: 12px; padding: 15px; border: 1px solid rgba(255, 255, 255, 0.08); text-align: center; }
    .stat-box h3 { color: #9ca3af; font-size: 0.75rem; margin-bottom: 5px; text-transform: uppercase; }
    .stat-box p { color: #fff; font-size: 2rem; font-weight: 900; margin: 0; }
    .coach-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
    .coach-card { background: linear-gradient(145deg, #181a20 0%, #0e0f13 100%); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 20px; }
</style>

<div style="max-width: 1300px; margin: 2rem auto; padding: 0 20px;">
    <div class="bento-grid">
        <article class="bento-welcome">
            <span style="color: #ef4444; font-size: 0.8rem; font-weight: 800; letter-spacing: 2px;">🔥 ELRAM TRAINING CAMP</span>
            <h2 style="font-size: 2.2rem; font-weight: 800; color: #fff; margin: 10px 0;">Pusat Latihan Bela Diri Premium</h2>
            <p style="color: #d1d5db; line-height: 1.7;">Tempat terbaik untuk menempa fisik, disiplin, dan kemampuan bela diri kamu! Kami menyediakan berbagai cabang olahraga pilihan seperti <strong>Muay Thai, Boxing, Kickboxing, BJJ, hingga MMA</strong>.</p>
        </article>

        <aside class="bento-stats">
            <h3 style="color: #fff; font-size: 1rem; font-weight: 700; margin-bottom: 15px; text-transform: uppercase;">Ringkasan Camp</h3>
            <div class="stats-inner-grid">
                <div class="stat-box"><h3>Total Kelas</h3><p style="color:#ef4444;"><?php echo $totalKelas; ?></p></div>
                <div class="stat-box"><h3>Total Member</h3><p style="color:#ef4444;"><?php echo $totalAnggota; ?></p></div>
                <div class="stat-box"><h3>Total Coach</h3><p style="color:#f59e0b;"><?php echo $totalCoach; ?></p></div>
                <div class="stat-box"><h3>Sisa Kuota</h3><p style="color:#10b981;"><?php echo $totalKuota; ?></p></div>
            </div>
        </aside>
    </div>

    <section>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: #fff; font-size: 1.6rem; margin: 0;">Tim Coach & Pelatih Profesional</h2>
            <span style="background: rgba(255,255,255,0.05); color: #9ca3af; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem;">🏆 Instruktur Bersertifikat</span>
        </div>
        <div class="coach-grid">
            <?php foreach ($daftarCoach as $coach): ?>
                <div class="coach-card">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #dc2626; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.2rem;">
                            <?php echo strtoupper(substr(str_replace('Coach ', '', $coach['nama_coach']), 0, 1)); ?>
                        </div>
                        <div>
                            <h4 style="color: #fff; margin: 0; font-size: 1rem;"><?php echo htmlspecialchars($coach['nama_coach']); ?></h4>
                            <span style="color: #f59e0b; font-size: 0.75rem; font-weight: bold;">⚡ <?php echo $coach['total_kelas']; ?> Sesi Kelas</span>
                        </div>
                    </div>
                    <div style="background: rgba(9, 10, 13, 0.5); padding: 12px; border-radius: 8px;">
                        <span style="color: #ef4444; font-size: 0.7rem; font-weight: 800; display: block; margin-bottom: 4px;">CABOR KEAHLIAN</span>
                        <p style="color: #d1d5db; font-size: 0.85rem; margin: 0;"><?php echo htmlspecialchars($coach['daftar_cabor']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>