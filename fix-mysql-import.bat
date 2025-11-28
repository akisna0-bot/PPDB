@echo off
echo Fixing MySQL Import Issues...

REM Stop MySQL if running
net stop mysql

REM Start MySQL with increased limits
echo Starting MySQL with optimized settings...
cd /d "C:\xampp\mysql\bin"

REM Create my.cnf with proper settings
echo [mysqld] > temp_my.cnf
echo max_allowed_packet=1G >> temp_my.cnf
echo innodb_buffer_pool_size=512M >> temp_my.cnf
echo wait_timeout=28800 >> temp_my.cnf
echo interactive_timeout=28800 >> temp_my.cnf

REM Start MySQL
net start mysql

echo MySQL restarted with optimized settings
echo Now try importing your SQL file again

pause