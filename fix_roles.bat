@echo off
echo ========================================
echo PERBAIKAN SEMUA ROLE LOGIN PPDB
echo ========================================
echo.

echo Pilih metode perbaikan:
echo 1. Jalankan script PHP (perlu koneksi database)
echo 2. Jalankan Laravel Seeder
echo 3. Tampilkan instruksi manual
echo.

set /p choice="Masukkan pilihan (1-3): "

if "%choice%"=="1" (
    echo.
    echo Menjalankan script PHP...
    php fix_all_roles.php
    pause
) else if "%choice%"=="2" (
    echo.
    echo Menjalankan Laravel Seeder...
    php artisan db:seed --class=CompleteUserSeeder
    pause
) else if "%choice%"=="3" (
    echo.
    echo ========================================
    echo INSTRUKSI MANUAL
    echo ========================================
    echo.
    echo 1. Buka phpMyAdmin: http://localhost/phpmyadmin
    echo 2. Pilih database 'ppdb'
    echo 3. Klik tab 'SQL'
    echo 4. Copy-paste isi file 'fix_all_roles.sql'
    echo 5. Klik 'Go' untuk menjalankan
    echo.
    echo Kredensial login setelah perbaikan:
    echo - Admin: admin@ppdb.com / admin123
    echo - Kepsek: kepsek@ppdb.com / kepsek123  
    echo - Keuangan: keuangan@ppdb.com / keuangan123
    echo - Verifikator: verifikator@ppdb.com / verifikator123
    echo.
    pause
) else (
    echo Pilihan tidak valid!
    pause
)