@echo off
echo =====================================================
echo SETUP DATABASE PPDB - Laravel Migration
echo =====================================================

echo.
echo 1. Menjalankan migrasi database...
php artisan migrate:fresh --force

echo.
echo 2. Menjalankan seeder data awal...
php artisan db:seed --class=DatabaseSeeder

echo.
echo 3. Membuat storage link...
php artisan storage:link

echo.
echo 4. Clear cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo =====================================================
echo DATABASE SETUP SELESAI!
echo =====================================================
echo.
echo Login Credentials:
echo - Admin: admin@ppdb.com / password
echo - Verifikator: verifikator@ppdb.com / password  
echo - Keuangan: keuangan@ppdb.com / password
echo - Kepsek: kepsek@ppdb.com / password
echo =====================================================

pause