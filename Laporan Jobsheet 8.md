# Desain dan Pemrograman Web

|  | Keterangan |
|--|--|
| **NIM** | 254107020255 |
| **Nama** | Daniel Ramadhani Zulkarnain |
| **Kelas** | TI - 2D |
| **Repository** | (https://github.com/danielramadhaniz031006/Desain-dan-Pemograman-Web_DanielRamadhaniZ/tree/5bcada813548d11e1446610e0428e3b51c8c295c/jobsheet-08) |

---

## 1. Menangani Error `UNIQUE` dengan Rapi

Tangani error `UNIQUE` dengan rapi — bungkus `$stmt->execute(...)` di `anggota/proses_tambah.php` dengan `try/catch (PDOException $e)`, lalu set `$_SESSION['flash']` berisi pesan seperti **"No. Anggota sudah dipakai, gunakan nomor lain."** alih-alih membiarkan error mentah ditampilkan kepada pengguna.

### Kode

    try {
        $stmt->execute([
            'nama' => $nama,
            'no_anggota' => $noAnggota,
            'alamat' => $alamat,
            'no_hp' => $noHp,
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Anggota berhasil ditambahkan.'
        ];

        header('Location: list.php');
        exit;

    } catch (PDOException $e) {

        if ($e->getCode() === '23505') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'pesan' => 'Data anggota gagal disimpan. Silakan coba lagi.'
            ];
        }

        header('Location: tambah.php');
        exit;
    }

### Penjelasan Tiap Baris Kode

**`try {`**

Digunakan untuk mencoba menjalankan proses penyimpanan data ke database.

**`$stmt->execute([`**

Menjalankan perintah SQL `INSERT` yang sebelumnya sudah dibuat menggunakan `prepare()`.

**`'nama' => $nama,`**

Mengisi parameter `:nama` dengan nilai dari variabel `$nama`.

**`'no_anggota' => $noAnggota,`**

Mengisi parameter `:no_anggota` dengan nomor anggota yang dimasukkan oleh pengguna.

**`'alamat' => $alamat,`**

Mengisi parameter `:alamat` dengan alamat anggota.

**`'no_hp' => $noHp,`**

Mengisi parameter `:no_hp` dengan nomor HP anggota.

**`]);`**

Menutup data parameter yang dikirimkan ke database.

**`$_SESSION['flash'] = [`**

Menyimpan pesan sementara ke dalam session agar dapat ditampilkan pada halaman berikutnya.

**`'type' => 'success',`**

Menentukan bahwa pesan yang disimpan merupakan pesan keberhasilan.

**`'pesan' => 'Anggota berhasil ditambahkan.'`**

Menentukan isi pesan ketika data anggota berhasil disimpan.

**`];`**

Menutup array pesan session.

**`header('Location: list.php');`**

Mengalihkan pengguna ke halaman daftar anggota setelah data berhasil ditambahkan.

**`exit;`**

Menghentikan proses PHP setelah melakukan pengalihan halaman.

**`} catch (PDOException $e) {`**

Menangkap error database yang terjadi pada proses `try`.

**`if ($e->getCode() === '23505') {`**

Memeriksa apakah error yang terjadi merupakan error `UNIQUE` PostgreSQL dengan kode `23505`.

**`$_SESSION['flash'] = [`**

Menyimpan pesan error ke dalam session.

**`'type' => 'error',`**

Menentukan bahwa pesan tersebut merupakan pesan error.

**`'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.'`**

Memberikan pesan yang mudah dipahami ketika nomor anggota yang dimasukkan sudah digunakan.

**`} else {`**

Dijalankan apabila error yang terjadi bukan merupakan error `UNIQUE`.

**`'pesan' => 'Data anggota gagal disimpan. Silakan coba lagi.'`**

Menampilkan pesan umum jika terjadi error database lainnya.

**`header('Location: tambah.php');`**

Mengembalikan pengguna ke halaman tambah anggota agar dapat memperbaiki data.

**`exit;`**

Menghentikan proses PHP setelah pengguna diarahkan kembali ke halaman tambah anggota.

---

## Hasil

Jika pengguna memasukkan **No. Anggota yang sudah digunakan**, sistem tidak lagi menampilkan error PostgreSQL secara mentah.




Sistem akan menampilkan pesan:

**"No. Anggota sudah dipakai, gunakan nomor lain."**

![Hasil Penanganan Error UNIQUE](https://github.com/user-attachments/assets/1efc8ae0-5ee6-401a-b67c-f9f8689575b1)


## 2. Menambahkan Kolom `tanggal_ditambahkan`

Tambah kolom baru — misalnya tanggal_ditambahkan TIMESTAMP DEFAULT NOW() di tabel buku (cari tahu sendiri arti NOW() dan TIMESTAMP lewat dokumentasi PostgreSQL), lalu tampilkan kolom itu di buku/list.php.

### Kode SQL

    ALTER TABLE buku
    ADD COLUMN IF NOT EXISTS tanggal_ditambahkan TIMESTAMP DEFAULT NOW();

### Penjelasan Tiap Baris Kode

**`ALTER TABLE buku`**

Digunakan untuk mengubah struktur tabel `buku` yang sudah ada di database.

**`ADD COLUMN IF NOT EXISTS tanggal_ditambahkan`**

Digunakan untuk menambahkan kolom baru bernama `tanggal_ditambahkan`. Kata `IF NOT EXISTS` digunakan agar kolom tidak ditambahkan kembali jika kolom tersebut sudah tersedia.

**`TIMESTAMP`**

Digunakan sebagai tipe data untuk menyimpan tanggal dan waktu.

**`DEFAULT NOW()`**

Menentukan nilai bawaan kolom menggunakan waktu saat ini. Fungsi `NOW()` akan menghasilkan tanggal dan waktu ketika data dimasukkan ke dalam tabel.

### Perubahan pada `buku/list.php`

Sebelumnya, tabel daftar buku hanya menampilkan kolom Judul, Pengarang, Tahun, Stok, dan Aksi. Data buku diambil menggunakan query `SELECT * FROM buku ORDER BY id DESC`. 

Kemudian ditambahkan kolom **Tanggal Ditambahkan** pada bagian tabel.

Kode yang ditambahkan pada bagian `<thead>`:

    <th>Tanggal Ditambahkan</th>

Kode tersebut digunakan untuk membuat judul kolom baru pada tabel.

Kemudian ditambahkan kode pada bagian `<tbody>`:

    <td>
        <?php
        echo date(
            'd-m-Y H:i',
            strtotime($buku['tanggal_ditambahkan'])
        );
        ?>
    </td>

### Penjelasan Kode Tampilan Tanggal

**`<td>`**

Digunakan untuk membuat sel pada tabel yang berisi data tanggal ditambahkan.

**`$buku['tanggal_ditambahkan']`**

Mengambil nilai `tanggal_ditambahkan` dari data buku yang diperoleh dari database.

**`strtotime($buku['tanggal_ditambahkan'])`**

Mengubah nilai tanggal dari database menjadi format waktu yang dapat diproses oleh PHP.

**`date('d-m-Y H:i', ...)`**

Mengubah format tanggal dan waktu agar lebih mudah dibaca. Format yang digunakan adalah tanggal-bulan-tahun dan jam:menit.

### Perubahan `proses_tambah.php`

File `buku/proses_tambah.php` tidak perlu mengisi `tanggal_ditambahkan` secara manual. Proses `INSERT` tetap memasukkan data judul, pengarang, tahun, ISBN, stok, dan kategori. Karena kolom `tanggal_ditambahkan` memiliki `DEFAULT NOW()`, PostgreSQL akan mengisi tanggal dan waktu secara otomatis ketika buku ditambahkan.

### Hasil

<img width="1297" height="330" alt="image" src="https://github.com/user-attachments/assets/e2470812-31ed-45e3-99a2-ed3efb7ece8c" />


## 3. Membuat Query Pencarian di Server

Buat query pencarian di server — tambahkan WHERE judul ILIKE :keyword (ILIKE = pencocokan teks tanpa memandang huruf besar/kecil di PostgreSQL) ke query SELECT di buku/list.php, dihubungkan dengan kolom pencarian yang sudah ada di HTML — bandingkan dengan filter tabel sisi klien yang sudah kamu bangun di dokumentasi jobsheet-05 §6.

### Kode

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

    $daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);

### Penjelasan Tiap Baris Kode

**`$keyword = trim($_GET['keyword'] ?? '');`**

Mengambil nilai pencarian dari parameter `keyword` pada URL. Fungsi `trim()` digunakan untuk menghilangkan spasi yang tidak diperlukan pada awal dan akhir kata pencarian.

**`$stmt = $pdo->prepare(`**

Mempersiapkan query SQL menggunakan PDO agar query dapat dijalankan dengan parameter.

**`"SELECT *`**

Mengambil seluruh kolom data dari tabel `buku`.

**`FROM buku`**

Menentukan bahwa data yang dicari berasal dari tabel `buku`.

**`WHERE judul ILIKE :keyword`**

Menyaring data berdasarkan kolom `judul`. Operator `ILIKE` digunakan untuk pencarian yang tidak membedakan huruf besar dan huruf kecil.

**`ORDER BY id DESC`**

Mengurutkan data berdasarkan `id` dari yang terbesar ke yang terkecil, sehingga data terbaru ditampilkan lebih dahulu.

**`$stmt->execute([`**

Menjalankan query yang sudah dipersiapkan sebelumnya.

**`'keyword' => '%' . $keyword . '%'`**

Mengisi parameter `:keyword` dengan kata pencarian. Tanda `%` digunakan agar pencarian dapat menemukan kata yang berada di bagian mana pun dari judul.

Contohnya, jika pengguna mencari:

    muay

Maka judul seperti:

    Basic Muay Thai

tetap dapat ditemukan.

**`$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);`**

Mengambil seluruh hasil pencarian dari database dan menyimpannya ke dalam variabel `$daftarBuku`.

### Perubahan pada Kolom Pencarian

Kolom pencarian pada `buku/list.php` menggunakan parameter `keyword`:

    <input
        type="text"
        id="search-input"
        name="keyword"
        value="<?php echo htmlspecialchars($keyword); ?>"
        placeholder="Ketik judul buku..."
    >

Atribut `name="keyword"` digunakan agar nilai yang dimasukkan pengguna dapat dikirim ke server melalui metode `GET`.

Form pencarian menggunakan:

    <form method="GET" action="list.php">

Ketika tombol **Cari** ditekan, browser mengirimkan keyword ke `list.php`. PHP kemudian menggunakan keyword tersebut pada query `ILIKE`.

### Perbandingan Server-Side dan Client-Side

Sebelumnya, pencarian dilakukan di sisi klien menggunakan JavaScript. Fungsi `initTableFilter()` mengambil teks dari setiap baris tabel dan menyembunyikan baris yang tidak sesuai dengan keyword.

Pada pencarian server-side, keyword dikirim ke PHP terlebih dahulu. PHP menjalankan query PostgreSQL menggunakan `WHERE judul ILIKE :keyword`, kemudian hanya data yang sesuai dengan pencarian yang dikembalikan dari database.

**Client-side:**

Pencarian dilakukan pada data yang sudah ada di halaman menggunakan JavaScript.

**Server-side:**

Pencarian dilakukan langsung pada database PostgreSQL menggunakan query SQL.

### Hasil

<img width="1917" height="497" alt="image" src="https://github.com/user-attachments/assets/97a7dd30-df4a-44f7-9f2e-ea9032f70dba" />


## 4. Migrasi Data Lama

Migrasi data lama — coba tulis skrip PHP kecil terpisah yang membaca data/buku.json dari jobsheet-06 (dokumentasi jobsheet-06 §3) lalu memasukkan seluruh isinya ke tabel buku lewat INSERT — latihan bagus untuk memahami bagaimana data lama bisa "dipindahkan" ke database baru.

### Penjelasan setiap baris kode

1. `<?php`
   Digunakan untuk menandai awal kode PHP.

2. `require __DIR__ . '/includes/koneksi.php';`
   Memanggil file `koneksi.php` agar program dapat terhubung ke database
   melalui variabel `$pdo`.

3. `$file = __DIR__ . '/data/buku.json';`
   Menentukan lokasi file `buku.json` yang berisi data buku lama.

4. `$data = json_decode(file_get_contents($file), true);`
   Membaca isi file `buku.json`, kemudian mengubah data JSON menjadi array
   PHP menggunakan `json_decode()`.

5. `foreach ($data as $buku) {`
   Melakukan perulangan untuk mengambil setiap data buku yang ada di
   dalam file JSON.

6. `$stmt = $pdo->prepare("...");`
   Membuat perintah SQL `INSERT` yang akan digunakan untuk memasukkan data
   ke tabel `buku`.

7. `INSERT INTO buku (judul, pengarang, tahun, stok)`
   Menentukan kolom tabel `buku` yang akan diisi.

8. `VALUES (:judul, :pengarang, :tahun, :stok)`
   Menentukan parameter yang nantinya akan diisi dengan data dari JSON.

9. `$stmt->execute([...]);`
   Menjalankan perintah `INSERT` dan mengirimkan nilai dari setiap data buku.

10. `':judul' => $buku['judul']`
    Mengambil nilai `judul` dari data JSON dan memasukkannya ke kolom
    `judul`.

11. `':pengarang' => $buku['pengarang']`
    Mengambil nilai `pengarang` dari data JSON dan memasukkannya ke kolom
    `pengarang`.

12. `':tahun' => $buku['tahun']`
    Mengambil nilai `tahun` dari data JSON dan memasukkannya ke kolom
    `tahun`.

13. `':stok' => $buku['stok']`
    Mengambil nilai `stok` dari data JSON dan memasukkannya ke kolom
    `stok`.

14. `}`
    Menutup proses perulangan `foreach`.

15. `echo "Migrasi berhasil. Total data: " . count($data);`
    Menampilkan pesan bahwa proses migrasi berhasil serta menampilkan
    jumlah data buku yang telah diproses.

### Hasil

<img width="1320" height="916" alt="image" src="https://github.com/user-attachments/assets/f280cc01-7fb2-4242-84d5-d60a8d5053c7" />
