|  | Desain dan Pemograman Web |
|--|--|
| NIM | 254107020255 |
| Nama | Daniel Ramadhani Zulkarnain |
| Kelas | TI - 2D |
| Repository | [link] (https://github.com/danielramadhaniz031006/Desain-dan-Pemograman-Web_DanielRamadhaniZ/tree/c13b7ad798083e3426e5c849fb6aa6e731dd3d4e/jobsheet-03) |


1. Tambah breakpoint baru — misalnya @media (min-width: 1400px) untuk layar monitor sangat lebar, ubah main { max-width: 1000px; } (dari dokumentasi jobsheet-02)
   menjadi lebih lebar khusus di breakpoint ini.

- Kode :
<img width="342" height="297" alt="Screenshot 2026-09-06 121342" src="https://github.com/user-attachments/assets/67e04785-7cf3-4893-bb22-31188e0fdd5d" />

- Penjelasan :
<img width="1300" height="210" alt="image" src="https://github.com/user-attachments/assets/99547c15-e816-47a6-af37-77bd5f71ba92" />

- Hasil :
<img width="1917" height="912" alt="image" src="https://github.com/user-attachments/assets/bdf2d6d9-80ea-4e01-a7f7-fc2c34b7f408" />


2. Ubah breakpoint tablet dari 768px menjadi 900px, lalu amati di lebar layar berapa susunan kartu berubah — buktikan bahwa breakpoint memang bisa disesuaikan bebas sesuai kebutuhan desain.

- Kode
<img width="485" height="165" alt="image" src="https://github.com/user-attachments/assets/9d5f92b0-85e4-487f-89d4-3945e64c89cc" />

- Penjelasan
<img width="1315" height="197" alt="image" src="https://github.com/user-attachments/assets/3f095b09-8fd6-4128-ac55-aabe3f0a336a" />

- Hasil
<img width="1917" height="905" alt="image" src="https://github.com/user-attachments/assets/3a923ccf-2134-4163-94ce-5d0127e31dd0" />


3. Terapkan pola table-responsive ke elemen lain yang berpotensi melebar di layar sempit, misalnya kalau suatu saat kamu menambahkan blok kode <pre> yang panjangdi salah satu halaman.
- Kode
<img width="366" height="128" alt="image" src="https://github.com/user-attachments/assets/5f5c3c71-7e7e-4913-960c-ee1ef6aff143" />
  
- Penjelasan
<img width="1293" height="177" alt="image" src="https://github.com/user-attachments/assets/7eb3f690-acc9-4cf9-9604-299373c9d2fd" />

- Hasil
<img width="1917" height="911" alt="image" src="https://github.com/user-attachments/assets/9b969c9d-57b1-4ad8-a2c5-d7e587287284" />


4. Ubah posisi ikon hamburger — misalnya pindahkan .nav-toggle-label ke urutan terakhir di <header> (setelah <nav>) lalu amati apakah sibling combinator .nav-toggle:checked ~ nav di bab 3 §3.5 masih bekerja — ingat catatan bahwa combinator ~ mensyaratkan target berada setelah elemen sumbernya di HTML.

- Kode
<img width="687" height="391" alt="image" src="https://github.com/user-attachments/assets/95e46897-71d4-451e-8456-f10f149fd25a" />
  
- Penjelasan
<img width="862" height="722" alt="image" src="https://github.com/user-attachments/assets/7dbc48ec-fc92-4218-a329-86817cd4a863" />

- Hasil
<img width="1917" height="891" alt="image" src="https://github.com/user-attachments/assets/ace196b5-02c2-4283-b712-9473eb888673" />


5. Bandingkan dengan pendekatan mobile-first — coba tulis ulang style.css dari nol memakai @media (min-width: ...) alih-alih max-width, dan rasakan sendiri bedanya alur berpikirnya.
- Kode
<img width="702" height="476" alt="image" src="https://github.com/user-attachments/assets/0879945a-d756-4d2f-aea5-2adffb7dd51e" />


- Penjelasan
<img width="617" height="666" alt="image" src="https://github.com/user-attachments/assets/4563fba4-3e36-41ba-ac6a-1321dc97d42f" />

- Hasil
  Tampilan Desktop
<img width="1917" height="907" alt="image" src="https://github.com/user-attachments/assets/06ffb3f4-d672-4ff5-8079-5c06129c8d20" />


  Tampilan Mobile
  
<img width="720" height="1600" alt="WhatsApp Image 2026-09-06 at 13 40 50" src="https://github.com/user-attachments/assets/f8ec9f4d-6c10-4cb2-9c0d-af783dc24a56" />
