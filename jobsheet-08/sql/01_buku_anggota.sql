-- 1. UBAH NAMA KOLOM "alamat" MENJADI "cabor"
ALTER TABLE anggota RENAME COLUMN alamat TO cabor;

-- 2. RESET DAN ISI DATA TABEL BUKU (JADWAL KELAS)
TRUNCATE TABLE buku RESTART IDENTITY;

INSERT INTO buku (judul, pengarang, kategori, tahun, stok) VALUES
('Muay Thai Striking', 'Coach Alex', 'Senin & Rabu', 19, 5),
('Pro Boxing Class', 'Coach Rian', 'Selasa & Kamis', 20, 4),
('MMA Ground Work', 'Coach Michael', 'Jumat & Sabtu', 18, 3),
('Brazilian Jiu-Jitsu (BJJ)', 'Coach Dave', 'Senin & Kamis', 19, 2),
('Kickboxing Dutch Style', 'Coach Satria', 'Rabu & Sabtu', 20, 3),
('Wrestling & Takedown', 'Coach Denis', 'Selasa & Jumat', 17, 1),
('Strength & Conditioning', 'Coach Doni', 'Setiap Hari', 16, 5),
('Basic Muay Thai (Beginner)', 'Coach Alex', 'Sabtu & Minggu', 10, 0),
('Boxing Pad Work', 'Coach Rian', 'Senin & Jumat', 18, 4),
('Fit Boxing & Cardio', 'Coach Doni', 'Rabu & Minggu', 19, 6);

-- 3. RESET DAN ISI DATA TABEL ANGGOTA (A1 - A30)
TRUNCATE TABLE anggota RESTART IDENTITY;

INSERT INTO anggota (no_anggota, nama, cabor, no_hp) VALUES
('A1', 'Daniel Ramadhani Zulkarnain', 'Muay Thai Striking', '08123456789'),
('A2', 'Budi Santoso', 'Pro Boxing Class', '081234567890'),
('A3', 'Siti Nurhaliza', 'Brazilian Jiu-Jitsu (BJJ)', '081234567891'),
('A4', 'Agus Setiawan', 'Kickboxing Dutch Style', '081234567892'),
('A5', 'Dewi Lestari', 'Muay Thai Striking', '081234567893'),
('A6', 'Eko Prasetyo', 'Wrestling & Takedown', '081234567894'),
('A7', 'Rina Wijaya', 'Fit Boxing & Cardio', '081234567895'),
('A8', 'Ahmad Fauzi', 'Strength & Conditioning', '081234567896'),
('A9', 'Rizky Febrian', 'Brazilian Jiu-Jitsu (BJJ)', '081234567897'),
('A10', 'Indah Permata', 'Kickboxing Dutch Style', '081234567898'),
('A11', 'Doni Pratama', 'Basic Muay Thai (Beginner)', '081234567899'),
('A12', 'Maya Putri', 'Pro Boxing Class', '081234567800'),
('A13', 'Bayu Nugroho', 'Wrestling & Takedown', '081234567801'),
('A14', 'Citra Kirana', 'MMA Ground Work', '081234567802'),
('A15', 'Fajar Kurniawan', 'Brazilian Jiu-Jitsu (BJJ)', '081234567803'),
('A16', 'Gita Gutawa', 'Boxing Pad Work', '081234567804'),
('A17', 'Hendra Setiawan', 'Muay Thai Striking', '081234567805'),
('A18', 'Irma Suryani', 'Pro Boxing Class', '081234567806'),
('A19', 'Joko Widodo', 'Wrestling & Takedown', '081234567807'),
('A20', 'Kevin Sanjaya', 'MMA Ground Work', '081234567808'),
('A21', 'Larasati Anggraini', 'Brazilian Jiu-Jitsu (BJJ)', '081234567809'),
('A22', 'Muhammad Ali', 'Pro Boxing Class', '081234567810'),
('A23', 'Nanda Saputra', 'Kickboxing Dutch Style', '081234567811'),
('A24', 'Olivia Zalianty', 'Muay Thai Striking', '081234567812'),
('A25', 'Putra Perkasa', 'Wrestling & Takedown', '081234567813'),
('A26', 'Qory Sandioriva', 'Strength & Conditioning', '081234567814'),
('A27', 'Raden Saleh', 'Brazilian Jiu-Jitsu (BJJ)', '081234567815'),
('A28', 'Sari Roti', 'Fit Boxing & Cardio', '081234567816'),
('A29', 'Taufik Hidayat', 'Muay Thai Striking', '081234567817'),
('A30', 'Utama Jaya', 'Boxing Pad Work', '081234567818');