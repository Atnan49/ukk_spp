
INSERT INTO kelas (nama_kelas, kompetensi_keahlian) VALUES 
('XII RPL 1', 'Rekayasa Perangkat Lunak'),
('XII TKJ 1', 'Teknik Komputer dan Jaringan'),
('XI RPL 1', 'Rekayasa Perangkat Lunak');

-- Insert data SPP contoh
INSERT INTO spp (tahun, nominal) VALUES 
('2024/2025', 300000),
('2023/2024', 275000),
('2022/2023', 250000);

-- Insert user siswa contoh
INSERT INTO users (user_name, password, level) VALUES 
('siswa1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siswa'),
('siswa2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siswa');

-- Insert data siswa contoh
INSERT INTO siswa (id_user, id_spp, id_kelas, nisn, nis, nama_siswa, alamat_siswa, no_telepon_siswa) VALUES 
((SELECT id_user FROM users WHERE user_name = 'siswa1'), 1, 1, '2024001001', '24001', 'Ahmad Fauzi', 'Jl. Merdeka No. 123', '081234567890'),
((SELECT id_user FROM users WHERE user_name = 'siswa2'), 1, 2, '2024001002', '24002', 'Siti Nurhaliza', 'Jl. Sudirman No. 456', '081234567891');

-- Insert data wali siswa contoh
INSERT INTO wali_siswa (id_siswa, nama_wali, no_hp_wali, alamat_wali) VALUES 
(1, 'Budi Santoso', '081234567892', 'Jl. Merdeka No. 123'),
(1, 'Sari Dewi', '081234567893', 'Jl. Merdeka No. 123'),
(2, 'Agus Wijaya', '081234567894', 'Jl. Sudirman No. 456');

-- Insert data pengajuan uang saku contoh
INSERT INTO uang_saku_topup (id_siswa, id_wali, nominal, catatan, status) VALUES 
(1, 1, 50000, 'Uang saku minggu ini', 'pending'),
(1, 2, 75000, 'Tambahan untuk beli buku', 'approved'),
(2, 3, 100000, 'Uang saku bulanan', 'pending');
