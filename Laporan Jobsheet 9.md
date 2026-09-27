# Laporan Jobsheet 09

|  | Desain dan Pemograman Web |
|--|--|
| **NIM** | 254107020255 |
| **Nama** | Daniel Ramadhani Zulkarnain |
| **Kelas** | TI - 2D |
| **Repository** | [https://github.com/danielramadhaniz031006/projek_danielramadhaniz/tree/23053cba38314b39aaadfd15ff3a6cb56eb7afab/jobsheet-09]() |

## 1. Tambah Konfirmasi Ekstra Sebelum Update

Pada fitur Update anggota ditambahkan konfirmasi tambahan sebelum data diperbarui. Tujuannya adalah untuk memastikan pengguna benar-benar ingin menyimpan perubahan yang dilakukan. Pada file `anggota/edit.php`, form Update diberikan class `form-update` agar dapat dikenali oleh JavaScript.

Kode yang ditambahkan pada `anggota/edit.php` adalah `class="form-update"` pada form Update.

Pada file `assets/js/app.js` ditambahkan fungsi `initUpdateConfirm()` yang menggunakan `confirm()` untuk menampilkan pesan konfirmasi sebelum form dikirim. Jika pengguna memilih OK, proses Update dilanjutkan. Jika memilih Cancel, proses Update dibatalkan menggunakan `e.preventDefault()`.

Fungsi `initUpdateConfirm()` kemudian dipanggil bersama fungsi JavaScript lainnya pada bagian `DOMContentLoaded`.

Dengan perubahan tersebut, pengguna akan mendapatkan konfirmasi sebelum melakukan Update data anggota. Hal ini membantu mencegah perubahan data yang tidak disengaja.

## 2. Ubah Jumlah Baris Per Halaman

Pada file `buku/list.php`, jumlah data yang ditampilkan dalam satu halaman diubah dari 5 menjadi 10.

Perubahan kode:

    $perPage = 5;

menjadi:

    $perPage = 10;

Perhitungan jumlah halaman tetap menggunakan nilai `$perPage` sehingga pagination akan otomatis menyesuaikan jumlah data yang tersedia.

Kode perhitungan pagination:

    $totalPages = max(1, (int) ceil($totalRows / $perPage));

Dengan perubahan tersebut, maksimal 10 data buku dapat ditampilkan dalam satu halaman. Jika jumlah data lebih banyak dari 10, sistem akan membuat halaman berikutnya secara otomatis.

## 3. Tambah Pencarian di Kolom Pengarang

Pada file `buku/list.php`, fitur pencarian yang sebelumnya hanya berdasarkan kolom `judul` diperluas agar dapat mencari berdasarkan kolom `pengarang`.

Sebelumnya query pencarian hanya menggunakan:

    WHERE judul ILIKE :kw

Kemudian diubah menjadi:

    WHERE judul ILIKE :kw
       OR pengarang ILIKE :kw

Perubahan tersebut diterapkan pada query untuk menghitung jumlah data dan query untuk mengambil data buku.

Operator `OR` digunakan agar kata kunci dapat dicocokkan dengan dua kolom. Dengan demikian, pengguna dapat mencari buku berdasarkan judul maupun nama pengarang. Contohnya, ketika pengguna mencari nama pengarang, sistem akan menampilkan buku yang memiliki nama tersebut pada kolom `pengarang`.

## 4. Terapkan Pola Update/Delete ke Fitur Lain

Pada proyek ini pola CRUD diterapkan pada entitas `buku` dan `anggota`. Pola tersebut menggunakan struktur file yang sama sehingga pengelolaan setiap data menjadi lebih teratur.

Struktur pada fitur `buku` terdiri dari `list.php`, `tambah.php`, `proses_tambah.php`, `edit.php`, `proses_edit.php`, dan `hapus.php`.

Struktur pada fitur `anggota` juga terdiri dari `list.php`, `tambah.php`, `proses_tambah.php`, `edit.php`, `proses_edit.php`, dan `hapus.php`.

File `list.php` digunakan untuk menampilkan data dan pagination. File `tambah.php` digunakan untuk menampilkan form penambahan data. File `proses_tambah.php` digunakan untuk memproses data yang ditambahkan. File `edit.php` digunakan untuk menampilkan form perubahan data. File `proses_edit.php` digunakan untuk memproses perubahan data. Sedangkan `hapus.php` digunakan untuk menghapus data.

Dengan menerapkan pola CRUD yang sama pada fitur buku dan anggota, struktur aplikasi menjadi lebih konsisten dan pola tersebut dapat digunakan kembali ketika menambahkan entitas baru pada proyek.
