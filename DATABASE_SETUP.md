# 🗄️ PANDUAN SETUP DATABASE PPDB

## 📋 Langkah-langkah Setup Database

### 1. **Setup Database MySQL**

#### Opsi A: Menggunakan phpMyAdmin
1. Buka `http://localhost/phpmyadmin`
2. Klik "New" untuk membuat database baru
3. Nama database: `ppdb_db`
4. Collation: `utf8mb4_unicode_ci`
5. Klik "Create"

#### Opsi B: Menggunakan MySQL Command Line
```sql
mysql -u root -p
CREATE DATABASE ppdb_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit
```

#### Opsi C: Import SQL File (Lengkap)
```bash
mysql -u root -p < database_setup.sql
```

### 2. **Setup Laravel Database**

#### Jalankan Script Otomatis
```bash
# Windows
setup_database.bat

# Manual
php artisan migrate:fresh --seed
```

### 3. **Verifikasi Setup**

Cek tabel yang terbuat:
- ✅ users
- ✅ majors  
- ✅ waves
- ✅ applicants
- ✅ applicant_files
- ✅ payments
- ✅ notifications

## 🔐 Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@ppdb.com | password |
| Verifikator | verifikator@ppdb.com | password |
| Keuangan | keuangan@ppdb.com | password |
| Kepsek | kepsek@ppdb.com | password |

## 📊 Struktur Database

### **Relasi Utama:**
```
users (1) ←→ (1) applicants
majors (1) ←→ (n) applicants  
waves (1) ←→ (n) applicants
applicants (1) ←→ (n) applicant_files
applicants (1) ←→ (n) payments
users (1) ←→ (n) notifications
```

### **Tabel Utama:**

#### 👤 **users**
- id, name, email, password, role
- Role: user, admin, verifikator_adm, keuangan, kepsek

#### 🎓 **majors** (Jurusan)
- id, code, name, kuota
- TKJ, RPL, MM, TKR, TSM, AKL

#### 🌊 **waves** (Gelombang)
- id, name, start_date, end_date, is_active

#### 📝 **applicants** (Pendaftar)
- Data pribadi: nama, nik, tempat_lahir, dll
- Alamat lengkap + koordinat
- Status: DRAFT, SUBMIT, VERIFIED, REJECTED
- Relasi: user_id, major_id, wave_id

#### 📄 **applicant_files** (Berkas)
- document_type: ktp, ijazah, transkrip, foto, dll
- file_path, status, catatan verifikasi

#### 💰 **payments** (Pembayaran)
- amount, payment_method, status
- payment_proof, verified_by

## 🚀 Setelah Setup

1. **Akses aplikasi**: `http://localhost/ppdb`
2. **Login sebagai admin**: admin@ppdb.com / password
3. **Test fitur export**: PDF & Excel sudah berfungsi
4. **Tambah data sample**: Gunakan seeder atau manual

## 🔧 Troubleshooting

### Error "Database not found"
```bash
# Pastikan database sudah dibuat
mysql -u root -p -e "SHOW DATABASES;"
```

### Error Migration
```bash
# Reset migrasi
php artisan migrate:fresh --force
```

### Error Permission
```bash
# Fix permission storage
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

## ✅ Checklist Setup

- [ ] Database `ppdb_db` sudah dibuat
- [ ] File `.env` sudah dikonfigurasi
- [ ] Migrasi berhasil dijalankan
- [ ] Seeder berhasil dijalankan  
- [ ] Login admin berhasil
- [ ] Export PDF/Excel berfungsi
- [ ] Upload file berfungsi

---
**🎉 Database PPDB siap digunakan!**