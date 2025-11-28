# LOGIN CREDENTIALS - PPDB SMK Bakti Nusantara 666

## User Accounts

### 1. Administrator
- **Email**: admin@ppdb.com
- **Password**: admin123
- **Role**: admin
- **Akses**: Semua fitur sistem

### 2. Verifikator Administrasi
- **Email**: verifikator@ppdb.com
- **Password**: verifikator123
- **Role**: verifikator
- **Akses**: Verifikasi berkas dan data pendaftar

### 3. Bagian Keuangan
- **Email**: keuangan@ppdb.com
- **Password**: keuangan123
- **Role**: keuangan
- **Akses**: Verifikasi pembayaran

### 4. Kepala Sekolah
- **Email**: kepsek@ppdb.com
- **Password**: kepsek123
- **Role**: kepsek
- **Akses**: Dashboard eksekutif dan laporan

## Cara Login

1. Buka halaman login: http://localhost/ppdb/login
2. Masukkan email dan password sesuai role
3. Klik "Login"

## Status Flow Sistem

1. **DRAFT** → Form belum terkirim
2. **SUBMIT** → Menunggu verifikasi administrasi
3. **ADM_PASS** → Lulus administrasi (bisa bayar)
4. **ADM_REJECT** → Ditolak administrasi (perlu perbaikan)
5. **PAID** → Sudah terbayar

## Fitur yang Tersedia

- ✅ Pendaftaran online calon siswa
- ✅ Upload berkas dengan preview foto
- ✅ Verifikasi administrasi
- ✅ Verifikasi pembayaran
- ✅ Dashboard monitoring
- ✅ Laporan dan export data
- ✅ Peta sebaran pendaftar
- ✅ Status tracking real-time

## Database Setup

- Database: ppdb
- Migrasi: ✅ Completed
- Seeder: ✅ Basic data loaded
- Users: ✅ All roles created