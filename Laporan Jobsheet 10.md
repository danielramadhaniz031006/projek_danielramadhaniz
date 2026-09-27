# Laporan Jobsheet 10

|  | Desain dan Pemograman Web |
|--|--|
| **NIM** | 254107020255 |
| **Nama** | Daniel Ramadhani Zulkarnain |
| **Kelas** | TI - 2D |
| **Repository** | [https://github.com/danielramadhaniz031006/projek_danielramadhaniz/tree/51c6f88c2ae0c6f1ec308411a016082bf8c11b73/jobsheet-10]() |

## 1. Terapkan Kontrol Akses Berbasis Role

Pada fitur penghapusan anggota ditambahkan kontrol akses berdasarkan `role` pengguna. Tujuannya adalah agar tidak semua pengguna yang sudah login dapat menghapus data anggota.

Pada file `anggota/hapus.php`, setelah `require auth.php` ditambahkan pengecekan terhadap `$_SESSION['role']`.

Kode yang digunakan:

    if (($_SESSION['role'] ?? '') !== 'admin') {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Akses ditolak. Hanya admin yang boleh menghapus anggota.'
        ];
        header('Location: list.php');
        exit;
    }

Dengan aturan tersebut, pengguna dengan role `admin` dapat melakukan proses penghapusan anggota. Sedangkan pengguna dengan role `petugas` tidak diperbolehkan menghapus data dan akan diarahkan kembali ke halaman daftar anggota dengan pesan akses ditolak.

Pengecekan dilakukan setelah `require auth.php` sehingga pengguna harus sudah login terlebih dahulu sebelum pemeriksaan role dilakukan.

## 2. Tambah Fitur "Ingat Saya" (Remember Me)

Pada halaman login ditambahkan fitur **Ingat Saya** yang memungkinkan pengguna memilih agar informasi login dapat disimpan melalui cookie dalam waktu yang lebih lama.

Pada file `auth/login.php` ditambahkan checkbox:

    <label>
        <input type="checkbox" name="remember" value="1">
        Ingat Saya
    </label>

Kemudian pada file `auth/proses_login.php`, pilihan tersebut diperiksa menggunakan:

    $remember = isset($_POST['remember']);

Jika pengguna mencentang **Ingat Saya**, sistem membuat cookie menggunakan fungsi `setcookie()` dengan masa berlaku 30 hari.

Cookie diberikan beberapa atribut keamanan seperti `httponly`, `secure`, dan `samesite` untuk membantu mengurangi risiko penyalahgunaan cookie.

Fitur ini digunakan untuk latihan memahami penggunaan cookie dengan masa berlaku panjang. Cookie yang bertahan lebih lama memiliki risiko keamanan lebih besar dibandingkan session biasa karena apabila cookie dicuri, cookie tersebut dapat disalahgunakan selama masih berlaku. Pada aplikasi nyata, sebaiknya digunakan token acak yang disimpan dan divalidasi di server.

## 3. Batasi Percobaan Login yang Gagal

Pada proses login ditambahkan penghitung percobaan login yang gagal. Penghitung disimpan sementara menggunakan `$_SESSION`.

Pada file `auth/proses_login.php`, jumlah percobaan gagal disimpan berdasarkan username:

    $_SESSION['login_attempts'][$username]

Batas percobaan login yang digunakan adalah 3 kali. Jika username dan password salah, jumlah percobaan akan bertambah dan sistem menampilkan jumlah sisa percobaan kepada pengguna.

Contohnya:

    Username atau password salah. Sisa percobaan: 2 kali.

Jika pengguna sudah gagal sebanyak 3 kali, sistem akan menolak percobaan login berikutnya dan menampilkan peringatan:

    Login gagal 3 kali. Silakan coba lagi nanti.

Jika login berhasil, jumlah percobaan gagal untuk username tersebut akan dihapus menggunakan:

    unset($_SESSION['login_attempts'][$username]);

Dengan adanya pembatasan ini, sistem memiliki mekanisme sederhana untuk mengurangi percobaan login berulang yang dapat digunakan dalam serangan brute-force. Penyimpanan melalui session digunakan sebagai latihan dan belum merupakan mekanisme pembatasan yang lengkap untuk aplikasi produksi.

## 4. Uji Coba Guard Authentication Saat PostgreSQL Dimatikan

Pada pengujian ini dilakukan percobaan mengakses halaman `buku/tambah.php` ketika pengguna belum login dan layanan PostgreSQL dihentikan sementara.

Pada file `buku/tambah.php`, bagian `auth.php` dipanggil terlebih dahulu:

    require __DIR__ . '/../includes/auth.php';

File `auth.php` melakukan pengecekan apakah session `user_id` sudah tersedia. Jika pengguna belum login, sistem langsung mengarahkan pengguna ke halaman login:

    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit;
    }

Pengujian dilakukan dengan menghentikan sementara layanan PostgreSQL kemudian mengakses halaman `buku/tambah.php` tanpa login.

Hasil pengujian menunjukkan bahwa pengguna tetap diarahkan ke halaman Login dan tidak menampilkan error koneksi database.

Hal tersebut terjadi karena `auth.php` dijalankan sebelum proses yang membutuhkan koneksi database. Ketika pengguna belum login, proses langsung dihentikan menggunakan `exit`, sehingga koneksi database belum dijalankan.

Dengan demikian, pengujian membuktikan bahwa guard authentication tetap dapat bekerja meskipun PostgreSQL sedang tidak tersambung.
