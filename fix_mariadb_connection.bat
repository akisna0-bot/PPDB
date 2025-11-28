@echo off
echo Memperbaiki koneksi MariaDB...

echo 1. Menghentikan MySQL/MariaDB...
net stop mysql

echo 2. Backup konfigurasi lama...
copy "C:\xampp\mysql\bin\my.ini" "C:\xampp\mysql\bin\my.ini.backup"

echo 3. Menambahkan konfigurasi bind-address...
echo bind-address = 0.0.0.0 >> "C:\xampp\mysql\bin\my.ini"

echo 4. Memulai ulang MySQL/MariaDB...
net start mysql

echo 5. Selesai! Coba login lagi.
pause