|  | Desain dan Pemograman Web |
|--|--|
| NIM | 254107020255 |
| Nama | Daniel Ramadhani Zulkarnain |
| Kelas | TI - 2D |
| Repository | https://github.com/danielramadhaniz031006/Desain-dan-Pemograman-Web_DanielRamadhaniZ/tree/7c8b0c227dfb98572dd4e7c07f614dd5d525c66d/jobsheet-05 |

---

## 1. Tambah Validasi Field Baru

Menambahkan validasi pada field **ISBN** di form **Tambah Buku**. Field ISBN sebelumnya tidak wajib diisi. Validasi ditambahkan agar ISBN hanya dapat menerima **angka dan tanda hubung (-)**.

### Kode

<img width="687" height="196" alt="Kode Validasi ISBN" src="https://github.com/user-attachments/assets/db51c87e-14c7-457a-909e-980350c4b25f" />

### Penjelasan

<img width="1211" height="251" alt="Penjelasan Validasi ISBN" src="https://github.com/user-attachments/assets/3b2f82ea-10da-4773-9dbb-9e25626a0a50" />

### Hasil

<img width="1917" height="911" alt="Screenshot 2026-09-09 071201" src="https://github.com/user-attachments/assets/5171e061-2c9d-41a0-bbf1-bc145d4d0075" />


<br>
<br>

---

## 2. Tambah Animasi Sederhana pada `initNavToggle`

Menambahkan animasi sederhana pada menu navigasi dengan menggunakan class CSS `transition` pada `header nav` di `style.css`, sehingga menu dapat terbuka dan tertutup dengan efek geser yang lebih halus, bukan langsung muncul atau menghilang.

### Kode

**`app.js`**

<img width="700" height="311" alt="Kode app.js" src="https://github.com/user-attachments/assets/372df2a0-4e11-4212-bf6e-602cd4923377" />

**`style.css`**

<img width="580" height="105" alt="Kode style.css bagian transition" src="https://github.com/user-attachments/assets/0f89d78f-4711-4220-846d-96c75b3033ba" />

<img width="272" height="131" alt="Kode style.css bagian nav-open" src="https://github.com/user-attachments/assets/8d95aefe-5b9e-40a8-899c-74849943d6ae" />

### Penjelasan

<img width="870" height="630" alt="Penjelasan app.js" src="https://github.com/user-attachments/assets/85bfdafb-ba27-4985-a9ff-1be9eda26518" />

<img width="1005" height="442" alt="Penjelasan style.css" src="https://github.com/user-attachments/assets/2abe6ae0-bd5f-4ea6-9b70-de40516e61fd" />

### Hasil

<img width="1917" height="913" alt="Hasil Animasi Menu" src="https://github.com/user-attachments/assets/2e787bcb-cefd-4220-9f4e-70c8d83d2c93" />

---

## 3. Perluas `initTableFilter` untuk Pencarian pada Satu Kolom

Memperluas fungsi `initTableFilter` supaya pencarian dapat dibatasi hanya pada satu kolom saja, misalnya kolom **Judul**, bukan mencari pada seluruh teks baris.

Perubahan dilakukan dengan menggunakan `row.querySelector("td")` seperti pola yang sudah digunakan pada Bab 5 §5.4, sebagai pengganti `row.textContent`.

### Kode

<img width="690" height="320" alt="Kode initTableFilter" src="https://github.com/user-attachments/assets/2540bb95-0ceb-4a24-869c-c1693a43e460" />

### Penjelasan

<img width="1352" height="457" alt="Penjelasan initTableFilter" src="https://github.com/user-attachments/assets/86c6bd70-9cb8-4860-90b4-05e86442a203" />

### Hasil

<img width="1916" height="908" alt="Hasil Filter Tabel 1" src="https://github.com/user-attachments/assets/be45b1f1-4903-449c-baff-1d324402973d" />

<img width="1917" height="917" alt="Hasil Filter Tabel 2" src="https://github.com/user-attachments/assets/b333cd59-5204-448f-a848-651c9b7f7da4" />

---

## 4. Tambah Counter Jumlah Baris Setelah Difilter atau Dihapus

Menambahkan counter jumlah baris yang tersisa setelah tabel difilter atau setelah data dihapus.

Counter menampilkan informasi seperti:

> Menampilkan 3 dari 5 buku

Counter diperbarui setiap kali proses **filter** atau **hapus data** dilakukan.

### Kode

#### Fungsi Counter

<img width="712" height="472" alt="Kode Fungsi Counter" src="https://github.com/user-attachments/assets/f6a0202f-2584-43dc-8c16-e4ad8c17ccbb" />

#### Setelah Filter

<img width="395" height="66" alt="Kode Counter Setelah Filter" src="https://github.com/user-attachments/assets/fb938486-4fe5-4b12-b796-69a131175424" />

#### Setelah Hapus

<img width="487" height="127" alt="Kode Counter Setelah Hapus" src="https://github.com/user-attachments/assets/3639cde4-a089-491a-b7cf-6a51f180fad8" />

#### HTML

<img width="537" height="37" alt="Kode HTML Table Counter" src="https://github.com/user-attachments/assets/d0d2d63e-7f43-4d71-8c27-aef2e5114cc6" />

### Penjelasan

#### Fungsi Counter

<img width="702" height="685" alt="Penjelasan Fungsi Counter" src="https://github.com/user-attachments/assets/d0305590-0595-4f45-9502-a7d5c6bb023d" />

#### Setelah Filter

`updateTableCounter(table);`

Memanggil fungsi counter setelah proses filter selesai, sehingga jumlah buku yang tampil langsung diperbarui.

#### Setelah Hapus

`row.remove();`

Menghapus baris buku yang dipilih dari tabel.

`updateTableCounter(table);`

Memperbarui jumlah counter setelah baris buku dihapus.

#### HTML

`<p>`

Membuat elemen paragraf untuk menampilkan informasi jumlah buku.

`id="table-counter"`

Memberikan identitas agar elemen tersebut bisa ditemukan dan diubah melalui JavaScript.

**Menampilkan 5 dari 5 buku**

Teks awal yang ditampilkan sebelum pengguna melakukan filter atau menghapus data.

`</p>`

Menutup elemen paragraf.

### Hasil

<img width="1917" height="906" alt="Hasil Counter Awal" src="https://github.com/user-attachments/assets/492f03c4-e176-4287-8a79-9a44e57b66c0" />

<img width="1917" height="910" alt="Hasil Counter Setelah Filter" src="https://github.com/user-attachments/assets/8b91bfc6-304a-45c6-900d-49e1641f2458" />

<img width="1917" height="970" alt="Hasil Counter Setelah Hapus" src="https://github.com/user-attachments/assets/e45e5994-ff30-4b86-b4d3-dffcc9e23d5c" />

<img width="1917" height="908" alt="Hasil Counter Tabel" src="https://github.com/user-attachments/assets/0424d002-79a5-47cd-8335-62ce2079b01d" />

---

## 5. Refactor Validasi Menggunakan Array dan `forEach`

Melakukan refactor pada fungsi `initValidasiForm` agar nama field yang wajib divalidasi diambil dari sebuah **array/daftar**.

Sebelumnya validasi dapat ditulis menggunakan blok `if` secara terpisah untuk setiap field. Dengan refactor ini, proses validasi menjadi lebih ringkas dan mudah dikembangkan dengan menggunakan pola perulangan `forEach` seperti yang digunakan pada Bab 5 dan Bab 6.

### Kode

<img width="695" height="461" alt="Kode Refactor Validasi" src="https://github.com/user-attachments/assets/b5e7cfcd-f2ba-49fa-bdfc-0ae1d1c3bc11" />

### Penjelasan

#### `const fieldWajib = ["judul", "pengarang", "tahun", "stok"];`

Membuat array yang berisi nama-nama field yang wajib divalidasi.

#### `fieldWajib.forEach(function (namaField) {`

Melakukan perulangan untuk memeriksa setiap field yang terdapat di dalam array `fieldWajib`.

#### `const field = document.getElementById(namaField);`

Mengambil elemen form berdasarkan ID field yang sedang diperiksa.

#### `if (field && field.value.trim() === "") {`

Mengecek apakah field ditemukan dan apakah field tersebut masih kosong setelah spasi di awal dan akhir dihilangkan menggunakan `trim()`.

#### `field.setCustomValidity("Field ini wajib diisi.");`

Memberikan pesan validasi bahwa field tersebut wajib diisi.

#### `else if (field) {`

Jika field ditemukan dan sudah memiliki isi, maka validasi error akan dihapus.

#### `field.setCustomValidity("");`

Mengosongkan pesan error sehingga field dianggap valid.

Dengan menggunakan array dan `forEach`, validasi menjadi lebih **ringkas, terstruktur, dan mudah dikembangkan**. Jika ingin menambahkan field baru yang wajib diisi, cukup menambahkan nama field tersebut ke dalam array `fieldWajib`.
