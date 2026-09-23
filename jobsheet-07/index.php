<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';


/*
|--------------------------------------------------------------------------
| DATA ANGGOTA
|--------------------------------------------------------------------------
| 1 anggota Ramadan + 7 fighter BYON
*/

$anggotaSeedVersion = 'byon-fighters-v1';

if (
    !isset($_SESSION['anggota_seed_version']) ||
    $_SESSION['anggota_seed_version'] !== $anggotaSeedVersion
) {
    $_SESSION['anggota'] = [

        [
            'no_anggota' => '1',
            'nama' => 'Ramadan',
            'alamat' => 'Jalan Bunga Andong Barat',
            'no_hp' => '081234567891'
        ],

        [
            'no_anggota' => '2',
            'nama' => 'Jekson Karmela',
            'alamat' => 'Jalan Ahmad Yani',
            'no_hp' => '081200000002'
        ],

        [
            'no_anggota' => '3',
            'nama' => 'Aziz Calim',
            'alamat' => 'Jalan Sudirman',
            'no_hp' => '081200000003'
        ],

        [
            'no_anggota' => '4',
            'nama' => 'Danar Ilmawan',
            'alamat' => 'Jalan Pahlawan',
            'no_hp' => '081200000004'
        ],

        [
            'no_anggota' => '5',
            'nama' => 'Muhammad Ramadhani Londo',
            'alamat' => 'Jalan Diponegoro',
            'no_hp' => '081200000005'
        ],

        [
            'no_anggota' => '6',
            'nama' => 'Redho Rocky',
            'alamat' => 'Jalan Gatot Subroto',
            'no_hp' => '081200000006'
        ],

        [
            'no_anggota' => '7',
            'nama' => 'Felmy Sumaehe',
            'alamat' => 'Jalan Cenderawasih',
            'no_hp' => '081200000007'
        ],

        [
            'no_anggota' => '8',
            'nama' => 'Surya Dharma',
            'alamat' => 'Jalan Imam Bonjol',
            'no_hp' => '081200000008'
        ]
    ];

    $_SESSION['anggota_seed_version'] = $anggotaSeedVersion;
}


/*
|--------------------------------------------------------------------------
| DATA BUKU
|--------------------------------------------------------------------------
| 10 buku:
| 5 buku sepak bola
| 5 buku combat sport
*/

$bukuSeedVersion = 'football-combat-v2';

if (
    !isset($_SESSION['buku_seed_version']) ||
    $_SESSION['buku_seed_version'] !== $bukuSeedVersion
) {

    $_SESSION['buku'] = [

        /*
        |--------------------------------------------------------------------------
        | SEPAK BOLA
        |--------------------------------------------------------------------------
        */

        [
            'judul' => 'Soccernomics',
            'pengarang' => 'Simon Kuper & Stefan Szymanski',
            'tahun' => 2009,
            'isbn' => '9781568584256',
            'stok' => 4,
            'kategori' => 'non-fiksi'
        ],

        [
            'judul' => 'Inverting the Pyramid: A History of Football Tactics',
            'pengarang' => 'Jonathan Wilson',
            'tahun' => 2008,
            'isbn' => '9780752889955',
            'stok' => 3,
            'kategori' => 'non-fiksi'
        ],

        [
            'judul' => 'The Ball is Round: A Global History of Football',
            'pengarang' => 'David Goldblatt',
            'tahun' => 2006,
            'isbn' => '9780670914807',
            'stok' => 2,
            'kategori' => 'non-fiksi'
        ],

        [
            'judul' => 'Brilliant Orange: The Neurotic Genius of Dutch Football',
            'pengarang' => 'David Winner',
            'tahun' => 2001,
            'isbn' => '9780747553106',
            'stok' => 4,
            'kategori' => 'non-fiksi'
        ],

        [
            'judul' => 'The Miracle of Castel di Sangro',
            'pengarang' => 'Joe McGinniss',
            'tahun' => 2000,
            'isbn' => '9780767905992',
            'stok' => 3,
            'kategori' => 'non-fiksi'
        ],


        /*
        |--------------------------------------------------------------------------
        | COMBAT SPORT
        |--------------------------------------------------------------------------
        */

        [
            'judul' => 'Muay Thai: Advanced Thai Kickboxing Techniques',
            'pengarang' => 'Christoph Delp',
            'tahun' => 2004,
            'isbn' => '9781583941010',
            'stok' => 2,
            'kategori' => 'referensi'
        ],

        [
            'judul' => 'The Fighter\'s Mind: Inside the Mental Game',
            'pengarang' => 'Sam Sheridan',
            'tahun' => 2010,
            'isbn' => '9780802145017',
            'stok' => 3,
            'kategori' => 'non-fiksi'
        ],

        [
            'judul' => 'A Fighter\'s Heart: One Man\'s Journey Through the World of Fighting',
            'pengarang' => 'Sam Sheridan',
            'tahun' => 2007,
            'isbn' => '9780871139504',
            'stok' => 2,
            'kategori' => 'non-fiksi'
        ],

        [
            'judul' => 'Championship Fighting: Explosive Punching and Aggressive Defense',
            'pengarang' => 'Jack Dempsey',
            'tahun' => 2015,
            'isbn' => '9781501111488',
            'stok' => 3,
            'kategori' => 'referensi'
        ],

        [
            'judul' => 'Tao of Jeet Kune Do',
            'pengarang' => 'Bruce Lee',
            'tahun' => 1975,
            'isbn' => '9780897500487',
            'stok' => 4,
            'kategori' => 'referensi'
        ]
    ];

    $_SESSION['buku_seed_version'] = $bukuSeedVersion;
}


/*
|--------------------------------------------------------------------------
| HITUNG DATA UNTUK BERANDA
|--------------------------------------------------------------------------
*/

$totalBuku = count($_SESSION['buku']);
$totalAnggota = count($_SESSION['anggota']);

$bukuDipinjam = 3;
$bukuTerlambat = 12;

?>


<section>
    <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>

    <p>
        Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.
    </p>
</section>


<section>
    <h2>Ringkasan</h2>

    <article>
        <h3>Total Buku</h3>
        <p><?php echo $totalBuku; ?></p>
    </article>

    <article>
        <h3>Total Anggota</h3>
        <p><?php echo $totalAnggota; ?></p>
    </article>

    <article>
        <h3>Sedang Dipinjam</h3>
        <p><?php echo $bukuDipinjam; ?></p>
    </article>

    <article>
        <h3>Buku Terlambat</h3>
        <p><?php echo $bukuTerlambat; ?></p>
    </article>
</section>


<?php include __DIR__ . '/includes/footer.php'; ?>