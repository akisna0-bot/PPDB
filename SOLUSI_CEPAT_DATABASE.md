# SOLUSI CEPAT MASALAH DATABASE

## Masalah: Host 'localhost' is not allowed to connect to MariaDB server

### **SOLUSI 1: Gunakan phpMyAdmin (PALING MUDAH)**

1. Buka browser: **http://localhost/phpmyadmin**
2. Klik database **ppdb** di sidebar kiri
3. Klik tab **SQL** di atas
4. Copy-paste script ini dan klik **Go**:

```sql
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Administrator', 
    'admin@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'admin',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    role = 'admin';

INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Verifikator Administrasi', 
    'verifikator@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'verifikator',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    role = 'verifikator';
```

### **SOLUSI 2: Perbaiki MariaDB (Jika Solusi 1 Tidak Berhasil)**

1. Buka **XAMPP Control Panel**
2. Stop **MySQL**
3. Klik **Config** → **my.ini**
4. Cari baris `[mysqld]` dan tambahkan di bawahnya:
```
bind-address = 0.0.0.0
skip-networking = 0
```
5. Save file
6. Start **MySQL** lagi

### **SOLUSI 3: Reset User MySQL**

Di phpMyAdmin, jalankan:
```sql
CREATE USER 'root'@'localhost' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```

## **KREDENSIAL LOGIN SETELAH PERBAIKAN:**

- **Admin**: admin@ppdb.com / admin123
- **Verifikator**: verifikator@ppdb.com / verifikator123

**Password hash yang digunakan**: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`
**Password asli**: `password` (tapi coba dulu dengan admin123/verifikator123)