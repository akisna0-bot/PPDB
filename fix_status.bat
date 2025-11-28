@echo off
echo =====================================================
echo MEMPERBAIKI STATUS ENUM PPDB
echo =====================================================
echo.

echo Menjalankan perbaikan status enum...
mysql -u root -p ppdb_db < fix_status_enum.sql

echo.
echo Menjalankan migration Laravel...
php artisan migrate

echo.
echo Membersihkan cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo =====================================================
echo PERBAIKAN SELESAI!
echo =====================================================
echo.
echo Status yang sekarang digunakan:
echo - SUBMIT: Menunggu verifikasi
echo - VERIFIED: Lulus verifikasi  
echo - REJECTED: Ditolak
echo.
pause