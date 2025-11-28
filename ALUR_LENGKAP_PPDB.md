# Alur Lengkap PPDB SMK Bakti Nusantara 666

## 🔄 Alur Proses Lengkap

### 1. **SISWA DAFTAR** 
- Siswa register akun dan login
- Mengisi formulir pendaftaran lengkap
- Upload berkas persyaratan
- **Status: `SUBMIT`**

### 2. **VERIFIKATOR VERIFIKASI**
- Verifikator login dan melihat daftar pendaftar
- Memeriksa data dan berkas siswa
- Memberikan keputusan:
  - ✅ **VERIFIED**: Berkas lengkap dan valid
  - ❌ **REJECTED**: Berkas tidak memenuhi syarat
- **Status berubah ke: `PAYMENT_PENDING` (jika verified) atau `REJECTED`**

### 3. **SISWA MELAKUKAN PEMBAYARAN**
- Siswa yang diverifikasi dapat akses menu pembayaran
- Sistem otomatis membuat invoice pembayaran
- Siswa memilih metode pembayaran dan bayar
- Upload bukti pembayaran
- **Status tetap: `PAYMENT_PENDING`**

### 4. **KEUANGAN VERIFIKASI PEMBAYARAN**
- Tim keuangan login dan melihat daftar pembayaran
- Memeriksa bukti pembayaran siswa
- Memberikan konfirmasi:
  - ✅ **PAID**: Pembayaran valid
  - ❌ **REJECTED**: Pembayaran tidak valid
- **Status berubah ke: `PAYMENT_VERIFIED` (jika valid)**

### 5. **ADMIN MEMBERIKAN KEPUTUSAN AKHIR**
- Admin/Kepsek login dan melihat siswa yang sudah bayar
- Menentukan keputusan akhir berdasarkan kuota dan kriteria
- Memberikan keputusan:
  - ✅ **ACCEPTED**: Siswa diterima
  - ❌ **REJECTED**: Siswa tidak diterima
- **Status berubah ke: `FINAL_REVIEW`**
- **Field `final_status` diisi: `ACCEPTED` atau `REJECTED`**

### 6. **SISWA MELIHAT HASIL**
- Siswa login dan cek dashboard
- Melihat hasil akhir: DITERIMA atau TIDAK DITERIMA
- Mendapat notifikasi hasil keputusan

## 📊 Status Flow

```
SUBMIT → VERIFIED/REJECTED
   ↓
PAYMENT_PENDING → PAYMENT_VERIFIED
   ↓
FINAL_REVIEW (dengan final_status: ACCEPTED/REJECTED)
```

## 🎯 Role dan Akses

### **Siswa (User)**
- ✅ Daftar dan isi data
- ✅ Upload berkas
- ✅ Lakukan pembayaran (setelah verified)
- ✅ Lihat status dan hasil akhir

### **Verifikator**
- ✅ Lihat daftar pendaftar status `SUBMIT`
- ✅ Verifikasi berkas (VERIFIED/REJECTED)
- ✅ Beri catatan verifikasi

### **Keuangan**
- ✅ Lihat daftar pembayaran status `PAYMENT_PENDING`
- ✅ Verifikasi pembayaran (PAID/REJECTED)
- ✅ Beri catatan pembayaran

### **Admin/Kepsek**
- ✅ Lihat siswa status `PAYMENT_VERIFIED`
- ✅ Beri keputusan akhir (ACCEPTED/REJECTED)
- ✅ Kelola seluruh sistem

## 🔧 Fitur Otomatis

### **Setelah Verifikator Approve:**
1. Status berubah ke `PAYMENT_PENDING`
2. Sistem otomatis buat Payment record
3. Siswa dapat akses menu pembayaran
4. Kirim notifikasi ke siswa

### **Setelah Keuangan Approve:**
1. Status berubah ke `PAYMENT_VERIFIED`
2. Data masuk antrian keputusan admin
3. Kirim notifikasi ke admin

### **Setelah Admin Decide:**
1. Status berubah ke `FINAL_REVIEW`
2. Field `final_status` diisi
3. Kirim notifikasi hasil ke siswa

## 📱 Dashboard Siswa

### **Timeline Progress:**
1. **Pendaftaran** ✅ (setelah submit)
2. **Verifikasi** ✅ (setelah verified)
3. **Pembayaran** ✅ (setelah payment verified)
4. **Pengumuman** ✅ (setelah final decision)

### **Status yang Ditampilkan:**
- `SUBMIT`: "Menunggu Verifikasi"
- `PAYMENT_PENDING`: "Silakan Lakukan Pembayaran"
- `PAYMENT_VERIFIED`: "Menunggu Keputusan Akhir"
- `FINAL_REVIEW` + `ACCEPTED`: "SELAMAT! ANDA DITERIMA"
- `FINAL_REVIEW` + `REJECTED`: "MOHON MAAF"

## 🔐 Keamanan

- ✅ Role-based access control
- ✅ CSRF protection
- ✅ Input validation
- ✅ Foreign key constraints
- ✅ Log semua aktivitas

## 📋 Database Schema

### **Applicants Table:**
```sql
status ENUM('SUBMIT', 'VERIFIED', 'REJECTED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED', 'FINAL_REVIEW')
verified_by (FK to users)
verified_at (timestamp)
final_status ENUM('ACCEPTED', 'REJECTED')
final_notes (text)
decided_by (FK to users)
decided_at (timestamp)
```

### **Payments Table:**
```sql
status ENUM('pending', 'paid', 'failed', 'expired')
verified_by (FK to users)
verified_at (timestamp)
```

## 🚀 Testing Alur

1. **Buat akun siswa** → Isi pendaftaran → Upload berkas
2. **Login verifikator** → Verifikasi berkas siswa
3. **Login siswa** → Lakukan pembayaran
4. **Login keuangan** → Verifikasi pembayaran
5. **Login admin** → Beri keputusan akhir
6. **Login siswa** → Lihat hasil akhir

Sistem sudah siap untuk alur lengkap PPDB! 🎓