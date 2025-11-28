# Integrasi Verifikator dengan Timeline Pembayaran Siswa

## Overview
Sistem telah diintegrasikan sehingga ketika verifikator memverifikasi berkas siswa, siswa akan otomatis masuk ke timeline pembayaran dan dapat melakukan pembayaran.

## Alur Proses

### 1. Pendaftaran Siswa
- Siswa mendaftar dan mengisi data pribadi
- Status: `SUBMIT`
- Siswa upload berkas persyaratan

### 2. Verifikasi oleh Verifikator
- Verifikator melihat daftar pendaftar dengan status `SUBMIT`
- Verifikator memeriksa data dan berkas siswa
- Verifikator memberikan keputusan:
  - **VERIFIED**: Berkas lengkap dan valid
  - **REJECTED**: Berkas tidak memenuhi syarat

### 3. Otomatisasi Setelah Verifikasi
Ketika verifikator memverifikasi dengan status `VERIFIED`:

#### A. Update Data Applicant
- Status berubah menjadi `VERIFIED`
- Field `verified_by` diisi dengan ID verifikator
- Field `verified_at` diisi dengan timestamp verifikasi
- Field `catatan_verifikasi` diisi dengan catatan verifikator

#### B. Pembuatan Payment Otomatis
- Sistem otomatis membuat record Payment dengan:
  - `payment_type`: 'registration'
  - `amount`: 150000 (biaya pendaftaran default)
  - `status`: 'pending'
  - `expired_at`: 7 hari dari sekarang
  - `invoice_number`: auto-generated

#### C. Notifikasi ke Siswa
- Sistem mengirim notifikasi ke siswa
- Notifikasi disimpan di tabel notifications (jika ada)

### 4. Timeline Siswa Setelah Verifikasi
Setelah diverifikasi, siswa akan melihat:

#### Dashboard Update
- Progress bar menunjukkan tahap "Verifikasi" selesai
- Status berubah menjadi "Berkas Diverifikasi"
- Tombol "Lakukan Pembayaran" menjadi aktif
- Informasi verifikator dan waktu verifikasi ditampilkan

#### Akses Pembayaran
- Siswa dapat mengakses menu Pembayaran
- Melihat invoice yang sudah dibuat otomatis
- Dapat melakukan pembayaran untuk biaya pendaftaran

### 5. Proses Pembayaran
- Siswa memilih metode pembayaran
- Setelah pembayaran berhasil, status applicant berubah menjadi `PAID`
- Progress bar menunjukkan tahap "Pembayaran" selesai

## Perubahan Kode

### 1. Model Applicant
```php
// Tambahan field
'verified_by', 'verified_at', 'final_status', 'final_notes', 'decided_by', 'decided_at'

// Method baru
public function canMakePayment()
public function isVerified()
public function verifier()
public function decider()
```

### 2. WorkflowController
```php
// Update method verifyApplicant
- Otomatis buat payment setelah verifikasi
- Kirim notifikasi ke siswa
- Log aktivitas verifikator

// Method baru
private function createInitialPayment($applicant)
private function sendNotificationToStudent($applicant, $status, $notes)
```

### 3. PaymentController
```php
// Update validasi akses
- Gunakan method canMakePayment() dari model
- Pesan error yang lebih informatif
```

### 4. Dashboard Siswa
```php
// Update tampilan status
- Tampilkan informasi verifikator
- Tampilkan waktu verifikasi
- Tampilkan catatan verifikasi
- Tombol pembayaran aktif setelah verifikasi
```

### 5. Database Migration
```sql
-- Tambahan field di tabel applicants
verified_by (foreign key ke users)
verified_at (timestamp)
final_status (enum: ACCEPTED, REJECTED)
final_notes (text)
decided_by (foreign key ke users)
decided_at (timestamp)

-- Update enum status
status (enum: SUBMIT, VERIFIED, REJECTED, PAID)
```

## Fitur Tambahan

### 1. Payment Items
- Sistem mendukung multiple payment items
- Biaya pendaftaran, seragam, buku, praktikum
- Dapat dikonfigurasi melalui admin

### 2. Notifikasi
- Notifikasi otomatis ke siswa setelah verifikasi
- Tersimpan di database untuk tracking

### 3. Log Aktivitas
- Semua aktivitas verifikator tercatat
- Dapat diaudit untuk transparansi

## Cara Penggunaan

### Untuk Verifikator:
1. Login sebagai verifikator
2. Akses menu "Daftar Pendaftar"
3. Pilih siswa dengan status "SUBMIT"
4. Periksa data dan berkas
5. Berikan verifikasi (VERIFIED/REJECTED) dengan catatan
6. Sistem otomatis membuat payment untuk siswa yang diverifikasi

### Untuk Siswa:
1. Setelah berkas diverifikasi, cek dashboard
2. Lihat status "Berkas Diverifikasi"
3. Klik tombol "Lakukan Pembayaran"
4. Pilih metode pembayaran dan bayar
5. Status berubah menjadi "PAID"

## Testing
Untuk testing integrasi:
1. Buat akun siswa dan lengkapi pendaftaran
2. Login sebagai verifikator
3. Verifikasi berkas siswa
4. Login kembali sebagai siswa
5. Cek dashboard dan lakukan pembayaran

## Keamanan
- Validasi role untuk setiap akses
- CSRF protection pada form
- Validasi input pada setiap request
- Foreign key constraints di database