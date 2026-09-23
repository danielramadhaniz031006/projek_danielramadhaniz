<?php

require __DIR__ . '/includes/koneksi.php';

$file = __DIR__ . '/data/buku.json';

$data = json_decode(file_get_contents($file), true);

foreach ($data as $buku) {
    $stmt = $pdo->prepare("
        INSERT INTO buku (judul, pengarang, tahun, stok)
        VALUES (:judul, :pengarang, :tahun, :stok)
    ");

    $stmt->execute([
        ':judul' => $buku['judul'],
        ':pengarang' => $buku['pengarang'],
        ':tahun' => $buku['tahun'],
        ':stok' => $buku['stok']
    ]);
}

echo "Migrasi berhasil. Total data: " . count($data);