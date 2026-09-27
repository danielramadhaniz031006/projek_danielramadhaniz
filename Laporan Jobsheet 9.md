# Desain dan Pemrograman Web 

|  | Keterangan |
|--|--|
| **NIM** | 254107020255 |
| **Nama** | Daniel Ramadhani Zulkarnain |
| **Kelas** | TI - 2D |
| **Repository** | () |

## 1. Tangani error UNIQUE dengan rapi

Bungkus `$stmt->execute(...)` di anggota/proses_tambah.php dengan try/catch (PDOException $e), lalu set $_SESSION['flash'] berisi pesan seperti "No. Anggota sudah dipakai, gunakan nomor lain." alih-alih membiarkan error mentah ditampilkan ke pengguna.

### Kode :

<img width="605" height="623" alt="image" src="https://github.com/user-attachments/assets/3e861847-6dbf-44a4-82ac-dcacbe246487" />

### Penjelsana perline:

**Penjelasan:**

Kode diawali dengan session_start() untuk mengaktifkan session yang digunakan
untuk menyimpan pesan notifikasi. Selanjutnya, require digunakan untuk memanggil
file koneksi.php agar program dapat terhubung ke database.

Variabel $nama, $noAnggota, $alamat, dan $noHp digunakan untuk mengambil data
yang dikirimkan melalui form menggunakan $_POST. Fungsi trim() digunakan untuk
menghilangkan spasi yang tidak diperlukan pada awal dan akhir input.

Variabel $errors digunakan untuk menampung pesan kesalahan validasi. Program
kemudian memeriksa apakah nama dan nomor anggota sudah diisi. Jika salah satu
kosong, pesan kesalahan dimasukkan ke dalam array $errors.

Jika terdapat kesalahan, sistem menyimpan pesan tersebut ke $_SESSION['flash'],
kemudian mengarahkan pengguna kembali ke halaman tambah.php dan menghentikan
proses menggunakan exit.

Selanjutnya, proses penyimpanan data database diletakkan di dalam blok try.
Program menggunakan $pdo->prepare() untuk menyiapkan perintah SQL INSERT ke
tabel anggota. Data nama, nomor anggota, alamat, dan nomor HP kemudian
dimasukkan menggunakan $stmt->execute().

Jika proses penyimpanan berhasil, sistem membuat flash message dengan tipe
success dan pesan "Anggota berhasil ditambahkan.", kemudian pengguna diarahkan
ke halaman list.php.

Apabila terjadi kesalahan database, proses akan ditangkap oleh blok
catch (PDOException $e). Hal ini digunakan untuk menangani error database,
termasuk ketika nomor anggota yang dimasukkan sudah digunakan karena memiliki
aturan UNIQUE.

Ketika terjadi error tersebut, sistem tidak menampilkan pesan error database
mentah kepada pengguna. Sebaliknya, sistem menyimpan pesan
"No. Anggota sudah dipakai, gunakan nomor lain." ke dalam session dan
mengarahkan pengguna kembali ke halaman tambah.php.

Dengan penerapan try-catch ini, error UNIQUE dapat ditangani dengan lebih rapi
dan pengguna mendapatkan pesan yang mudah dipahami.

### Hasil :


## 2. Tambah kolom baru

Misalnya tanggal_ditambahkan TIMESTAMP DEFAULT NOW() di tabel buku (cari tahu sendiri arti NOW() dan TIMESTAMP lewat dokumentasi PostgreSQL), lalu tampilkan kolom itu di buku/list.php.

ADD COLUMN tanggal_ditambahkan TIMESTAMP DEFAULT NOW();

### Penjelasan:

ALTER TABLE buku digunakan untuk mengubah struktur tabel buku.

ADD COLUMN tanggal_ditambahkan digunakan untuk menambahkan kolom baru bernama tanggal_ditambahkan.

TIMESTAMP digunakan untuk menyimpan data tanggal dan waktu.

DEFAULT NOW() digunakan agar tanggal dan waktu terisi otomatis ketika data buku ditambahkan.

Pada file buku/list.php, ditambahkan:

<th>Tanggal Ditambahkan</th>

Kode tersebut digunakan untuk menampilkan judul kolom Tanggal Ditambahkan pada tabel daftar buku.

Kemudian digunakan kode:

<td>
    <?php
    if (!empty($buku['tanggal_ditambahkan'])) {
        echo htmlspecialchars(
            date(
                'd-m-Y H:i',
                strtotime($buku['tanggal_ditambahkan'])
            )
        );
    } else {
        echo '-';
    }
    ?>
</td>

Kode tersebut digunakan untuk mengambil tanggal dari database dan menampilkannya dalam format tanggal-bulan-tahun jam:menit. Jika data tanggal tidak tersedia, sistem akan menampilkan tanda -.
