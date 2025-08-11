# Fitur Uang Saku - Dokumentasi

## 📋 Deskripsi
Sistem uang saku memungkinkan wali siswa untuk mengajukan top-up uang saku yang kemudian dapat disetujui atau ditolak oleh admin.

## 🚀 Fitur Utama

### Untuk Admin/Petugas:
- ✅ Melihat semua pengajuan uang saku
- ✅ Menyetujui/menolak pengajuan
- ✅ Menambah data wali siswa
- ✅ Dashboard statistik pengajuan

### Untuk Siswa:
- ✅ Melihat saldo uang saku
- ✅ Melihat riwayat top-up
- ✅ Mendapatkan link untuk wali

### Untuk Wali:
- ✅ Mengajukan top-up via form public
- ✅ Form sederhana tanpa login

## 📊 Database Schema

### Tabel `wali_siswa`
```sql
- id_wali (PK)
- id_siswa (FK ke siswa)
- nama_wali
- no_hp_wali
- alamat_wali
```

### Tabel `uang_saku_topup`
```sql
- id_topup (PK)
- id_siswa (FK ke siswa)
- id_wali (FK ke wali_siswa)
- tanggal_pengajuan
- nominal
- catatan
- status (pending/approved/rejected)
- approved_by (FK ke users)
- approved_at
- rejection_reason
```

### View `vw_uang_saku_saldo`
Menghitung total saldo per siswa dari pengajuan yang sudah approved.

## 🔗 URL Routes

| URL | Akses | Deskripsi |
|-----|-------|-----------|
| `/uang_saku` | Login Required | Dashboard utama |
| `/uang_saku/pengajuan` | Admin/Petugas | Pengajuan pending |
| `/uang_saku/approve/{id}` | Admin/Petugas | Setujui pengajuan |
| `/uang_saku/reject` | Admin/Petugas | Tolak pengajuan |
| `/uang_saku/tambah_wali` | Admin | Form tambah wali |
| `/uang_saku/pengajuan_wali` | Public | Form untuk wali |

## 👥 Akun Testing

### Admin
- **Username:** `admin`
- **Password:** `admin`

### Siswa
- **Username:** `siswa1` / `siswa2`
- **Password:** `admin`

## 📝 Cara Penggunaan

### 1. Setup Database
```bash
# Jalankan setup otomatis
http://localhost/ukk_spp/setup_database.php
```

### 2. Login sebagai Admin
1. Login dengan akun admin
2. Klik menu "Uang Saku"
3. Tambah data wali siswa melalui "Tambah Wali Siswa"

### 3. Pengajuan oleh Wali
1. Buka link: `http://localhost/ukk_spp/uang_saku/pengajuan_wali`
2. Isi form dengan ID Siswa dan ID Wali
3. Submit pengajuan

### 4. Approval oleh Admin
1. Login sebagai admin
2. Klik "Pengajuan Pending"
3. Klik tombol ✓ untuk setujui atau ✗ untuk tolak

### 5. Cek Saldo Siswa
1. Login sebagai siswa
2. Klik menu "Uang Saku"
3. Lihat saldo dan riwayat

## 🔧 Konfigurasi

### Validasi Nominal
- Minimal top-up: **Rp 10.000**
- Trigger database memvalidasi nominal > 0

### Status Pengajuan
- **pending**: Menunggu persetujuan
- **approved**: Disetujui, nominal masuk ke saldo
- **rejected**: Ditolak dengan alasan

### Permissions
- **Admin/Petugas**: Full access
- **Siswa**: Read-only untuk data sendiri
- **Wali**: Form submission only

## 🛠️ Files Structure

```
app/
├── controllers/
│   └── UangSakuController.php
├── models/
│   ├── UangSaku.php
│   ├── WaliSiswa.php
│   └── Siswa.php
└── controllers/view/uang_saku/
    ├── admin.php
    ├── siswa.php
    ├── pengajuan.php
    ├── tambah_wali.php
    └── form_wali.php
```

## 🔐 Security Features

1. **SQL Injection Protection**: Menggunakan `real_escape_string`
2. **Session Validation**: Cek login dan level user
3. **Input Validation**: Validasi nominal dan required fields
4. **Database Constraints**: FK constraints dan triggers
5. **CSRF Protection**: Form menggunakan POST method

## 📱 Responsive Design

Interface menggunakan Bootstrap 5 untuk tampilan yang responsive di desktop dan mobile.

## 🚨 Troubleshooting

### Error "Unknown column 'username'"
Pastikan kolom database menggunakan `user_name` bukan `username`.

### Error FK Constraint
Pastikan semua tabel menggunakan engine InnoDB.

### Login Issues
Cek session `$_SESSION['LOGIN']` dan `$_SESSION['id_user']`.

## 📞 Support

Untuk pertanyaan teknis, hubungi developer atau cek dokumentasi kode di repository.
