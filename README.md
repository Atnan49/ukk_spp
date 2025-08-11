# UKK SPP Project



# cara untuk SET UP WEB

# BUG
BUG DI UANG SAKU. belum ada koneksi uangsakucontroller ke home controler.

url.php di line 13-19 menggunakan sulusi sementara
fungtion.php di line 29-31 menggunakan sulusi sementara

## Deskripsi
Aplikasi ini adalah sistem pembayaran SPP berbasis web yang dirancang untuk mempermudah pengelolaan pembayaran siswa. Aplikasi ini menggunakan arsitektur MVC dengan teknologi PHP dan MySQL.

## Fitur
- Login dan Logout
- Validasi sesi pengguna
- Fitur "Remember Me" untuk login otomatis
- Pengelolaan data siswa, petugas, dan pembayaran
- Keamanan terhadap SQL Injection

## Struktur Direktori
```
app/
  controllers/   # Berisi file controller
  models/        # Berisi file model
  views/         # Berisi file view
core/            # Berisi file inti seperti routing dan fungsi umum
public/          # Berisi file yang dapat diakses publik seperti index.php dan assets
```

## Instalasi
1. Clone repository ini ke direktori server lokal Anda:
   ```bash
   git clone https://github.com/Atnan49/ukk_spp.git
   ```
2. Pastikan server lokal Anda memiliki PHP dan MySQL terinstal.
3. Import file database yang disediakan ke MySQL.
4. Konfigurasi file koneksi database jika diperlukan.
5. Akses aplikasi melalui browser di `http://localhost/ukk_spp`.

## Kontribusi
Jika Anda ingin berkontribusi pada proyek ini, silakan buat pull request atau laporkan masalah melalui halaman Issues.

## Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).
