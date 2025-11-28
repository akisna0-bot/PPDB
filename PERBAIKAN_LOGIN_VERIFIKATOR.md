# PERBAIKAN LOGIN VERIFIKATOR

## Masalah yang Ditemukan

Ada inkonsistensi dalam role verifikator di sistem:
1. Di `UserSeeder.php` role verifikator adalah `'verifikator_adm'`
2. Di `AdminSeeder.php` role verifikator adalah `'verifikator'`
3. Di `AuthenticatedSessionController.php` redirect untuk verifikator menggunakan `'verifikator_adm'`
4. Di `IsVerifikator.php` middleware mengecek `'verifikator'`

## Perbaikan yang Sudah Dilakukan

### 1. File yang Sudah Diperbaiki:

#### `app/Http/Middleware/IsVerifikator.php`
- Middleware sekarang mengecek role `'admin'` atau `'verifikator'`

#### `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- Redirect login sekarang menangani role `'verifikator'`

#### `database/seeders/UserSeeder.php`
- Role verifikator diubah dari `'verifikator_adm'` menjadi `'verifikator'`

#### `database/migrations/2025_11_18_031353_fix_verifikator_role_names.php`
- Migration untuk mengupdate role di database

### 2. Script yang Dibuat:

#### `manual_fix_verifikator.sql`
Script SQL untuk dijalankan manual di phpMyAdmin

#### `create_verifikator_simple.php`
Script PHP untuk memperbaiki user verifikator

## Cara Mengatasi Masalah Database

### Opsi 1: Menggunakan phpMyAdmin
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Pilih database `ppdb`
3. Jalankan query SQL berikut:

```sql
-- Update role verifikator_adm menjadi verifikator
UPDATE users SET role = 'verifikator' WHERE role = 'verifikator_adm';

-- Pastikan user verifikator ada
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Verifikator Administrasi', 
    'verifikator@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'verifikator',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    role = 'verifikator',
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
```

### Opsi 2: Memperbaiki Koneksi Database
1. Pastikan XAMPP MySQL/MariaDB berjalan
2. Buka file `c:\xampp\mysql\bin\my.ini`
3. Cari baris `bind-address` dan pastikan:
   ```
   bind-address = 127.0.0.1
   ```
4. Restart MySQL/MariaDB service
5. Jalankan script: `php create_verifikator_simple.php`

### Opsi 3: Menggunakan Artisan Seeder
1. Perbaiki koneksi database terlebih dahulu
2. Jalankan: `php artisan db:seed --class=UserSeeder`

## Kredensial Login Verifikator

Setelah perbaikan, gunakan kredensial berikut:

**Email:** verifikator@ppdb.com  
**Password:** verifikator123

## Kredensial Login Lainnya

**Admin:**
- Email: admin@ppdb.com
- Password: admin123

**Kepala Sekolah:**
- Email: kepsek@ppdb.com
- Password: kepsek123

**Keuangan:**
- Email: keuangan@ppdb.com
- Password: keuangan123

## Verifikasi Perbaikan

1. Buka browser dan akses: http://localhost/ppdb/login
2. Login dengan kredensial verifikator
3. Seharusnya diarahkan ke: http://localhost/ppdb/verifikator/dashboard

## Troubleshooting

### Jika masih tidak bisa login:
1. Cek apakah user ada di database:
   ```sql
   SELECT * FROM users WHERE email = 'verifikator@ppdb.com';
   ```

2. Cek role user:
   ```sql
   SELECT name, email, role FROM users WHERE role IN ('admin', 'verifikator', 'kepsek', 'keuangan');
   ```

3. Reset password manual:
   ```sql
   UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
   WHERE email = 'verifikator@ppdb.com';
   ```

### Jika error 403 Forbidden:
- Pastikan middleware `IsVerifikator` sudah diperbaiki
- Clear cache Laravel: `php artisan cache:clear`
- Clear config cache: `php artisan config:clear`