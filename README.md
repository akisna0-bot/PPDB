# PPDB SMK BAKTI NUSANTARA 666

Sistem Penerimaan Peserta Didik Baru (PPDB) berbasis web menggunakan Laravel.

## Fitur Utama

- **Registrasi Siswa** - Pendaftaran online tanpa OTP
- **Dashboard Multi-Role** - Admin, Verifikator, Keuangan, Kepala Sekolah
- **Upload Dokumen** - Sistem upload berkas pendaftaran
- **Pembayaran Online** - Integrasi pembayaran dengan QRIS
- **Verifikasi Berkas** - Workflow verifikasi dokumen
- **Laporan & Statistik** - Dashboard analitik lengkap
- **Peta Sebaran** - Visualisasi geografis pendaftar

## Teknologi

- **Framework**: Laravel 10
- **Database**: SQLite/MySQL
- **Frontend**: Blade Templates, Tailwind CSS
- **Maps**: Leaflet.js
- **Export**: DomPDF, Maatwebsite Excel

## Instalasi

1. Clone repository
```bash
git clone https://github.com/akisna0-bot/PPDB.git
cd PPDB
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Setup database
```bash
php artisan migrate
php artisan db:seed
```

5. Run server
```bash
php artisan serve
```

## Login Credentials

- **Admin**: admin@ppdb.com / password
- **Verifikator**: verifikator@ppdb.com / password  
- **Keuangan**: keuangan@ppdb.com / password
- **Kepsek**: kepsek@ppdb.com / password

## License

MIT License