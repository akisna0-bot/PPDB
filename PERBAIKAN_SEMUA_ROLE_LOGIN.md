# PERBAIKAN SEMUA ROLE LOGIN

## Masalah yang Ditemukan

1. **Inkonsistensi Role**: Role verifikator ada yang `'verifikator_adm'` dan `'verifikator'`
2. **Password Berbeda**: AdminSeeder dan UserSeeder menggunakan password yang berbeda
3. **User Duplikat**: Kemungkinan ada user duplikat dengan email sama

## Perbaikan yang Sudah Dilakukan

### 1. File yang Diperbaiki:

#### `database/seeders/AdminSeeder.php`
- Ditambahkan user admin
- Password diseragamkan dengan UserSeeder
- Role verifikator dipastikan konsisten

#### `database/seeders/CompleteUserSeeder.php` (BARU)
- Seeder lengkap dengan updateOrCreate untuk menghindari duplikat

#### `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- Redirect login sudah diperbaiki untuk semua role

#### Semua Middleware sudah konsisten:
- `IsAdmin.php` - mengecek role 'admin'
- `IsKepsek.php` - mengecek role 'kepsek'  
- `IsKeuangan.php` - mengecek role 'keuangan'
- `IsVerifikator.php` - mengecek role 'verifikator'
- `AdminOrKepsek.php` - mengecek role 'admin' atau 'kepsek'
- `AdminOrKeuangan.php` - mengecek role 'admin' atau 'keuangan'

## Cara Memperbaiki Database

### Opsi 1: Menggunakan phpMyAdmin (TERMUDAH)

1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Pilih database `ppdb`
3. Klik tab "SQL"
4. Copy-paste dan jalankan script dari file `fix_all_roles.sql`

### Opsi 2: Menggunakan MySQL Command Line

```bash
mysql -u root -p ppdb < fix_all_roles.sql
```

### Opsi 3: Memperbaiki Koneksi Database Lalu Jalankan Script

1. **Perbaiki koneksi MariaDB:**
   - Buka `c:\xampp\mysql\bin\my.ini`
   - Pastikan ada baris: `bind-address = 127.0.0.1`
   - Restart MySQL service di XAMPP

2. **Jalankan script PHP:**
   ```bash
   php fix_all_roles.php
   ```

3. **Atau gunakan Artisan:**
   ```bash
   php artisan db:seed --class=CompleteUserSeeder
   ```

## Kredensial Login Setelah Perbaikan

| Role | Email | Password | Dashboard |
|------|-------|----------|-----------|
| **Admin** | admin@ppdb.com | admin123 | /admin/dashboard |
| **Kepala Sekolah** | kepsek@ppdb.com | kepsek123 | /kepsek/dashboard |
| **Keuangan** | keuangan@ppdb.com | keuangan123 | /keuangan/dashboard |
| **Verifikator** | verifikator@ppdb.com | verifikator123 | /verifikator/dashboard |

## Verifikasi Perbaikan

### 1. Test Login Semua Role:
```bash
# Buka browser dan test login dengan masing-masing kredensial
http://localhost/ppdb/login
```

### 2. Cek Database:
```sql
SELECT name, email, role FROM users 
WHERE role IN ('admin', 'verifikator', 'kepsek', 'keuangan') 
ORDER BY role;
```

### 3. Test Redirect:
- Admin → `/admin/dashboard`
- Kepsek → `/kepsek/dashboard`  
- Keuangan → `/keuangan/dashboard`
- Verifikator → `/verifikator/dashboard`

## Troubleshooting

### Jika masih tidak bisa login:

1. **Cek user di database:**
   ```sql
   SELECT * FROM users WHERE email = 'admin@ppdb.com';
   ```

2. **Reset password manual:**
   ```sql
   UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
   WHERE email = 'admin@ppdb.com';
   ```
   (Password hash untuk 'password')

3. **Clear cache Laravel:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   ```

### Jika error 403 Forbidden:
- Pastikan middleware sudah benar
- Cek role di database sesuai dengan yang diharapkan middleware

### Jika redirect tidak benar:
- Cek `AuthenticatedSessionController.php`
- Pastikan role di database sesuai dengan kondisi di controller

## Script yang Tersedia

1. **`fix_all_roles.sql`** - Script SQL manual
2. **`fix_all_roles.php`** - Script PHP otomatis  
3. **`CompleteUserSeeder.php`** - Seeder Laravel lengkap

Pilih salah satu metode sesuai dengan kondisi sistem Anda.