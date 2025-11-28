# Status Flow PPDB - Sesuai Spesifikasi

## Alur Status Pendaftaran

### 1. DRAFT
- **Keterangan**: Form belum terkirim, masih dalam tahap pengisian
- **Aksi**: Siswa masih mengisi data dan upload berkas
- **Selanjutnya**: SUBMIT

### 2. SUBMIT  
- **Keterangan**: Form terkirim, menunggu verifikasi administrasi
- **Aksi**: Verifikator administrasi memeriksa data dan berkas
- **Selanjutnya**: ADM_PASS atau ADM_REJECT

### 3. ADM_PASS
- **Keterangan**: Lulus Administrasi - Berkas memenuhi syarat
- **Aksi**: Siswa melakukan pembayaran
- **Selanjutnya**: PAID

### 4. ADM_REJECT
- **Keterangan**: Ditolak Administrasi - Berkas tidak memenuhi syarat
- **Aksi**: Siswa memperbaiki berkas dan submit ulang
- **Selanjutnya**: SUBMIT (setelah perbaikan)

### 5. PAID
- **Keterangan**: Terbayar - Siswa sudah bayar dan menunggu verifikasi keuangan
- **Aksi**: Bagian keuangan memverifikasi pembayaran
- **Status Final**: Proses selesai

## Peran dan Aksi

### Calon Siswa
- Mengisi form (DRAFT → SUBMIT)
- Upload berkas
- Melakukan pembayaran (ADM_PASS → PAID)
- Memperbaiki berkas jika ditolak (ADM_REJECT → SUBMIT)

### Verifikator Administrasi  
- Memeriksa data dan berkas (SUBMIT → ADM_PASS/ADM_REJECT)
- Memberikan catatan verifikasi

### Bagian Keuangan
- Memverifikasi pembayaran (PAID)
- Konfirmasi pembayaran valid

### Admin/Kepala Sekolah
- Monitoring semua status
- Laporan dan dashboard

## Update yang Dilakukan

1. ✅ Enum status diperbaiki sesuai spesifikasi
2. ✅ Interface verifikator diupdate
3. ✅ Dashboard menampilkan status yang benar
4. ✅ Alur pembayaran sesuai dengan ADM_PASS → PAID
5. ✅ Foto di verifikator sudah diperbaiki ukurannya

## Fitur Tambahan yang Sudah Ada

- Preview foto dengan modal
- Responsive design
- Image optimization
- Status tracking yang akurat
- Audit log untuk semua perubahan status