# 📸 Panduan Menambahkan Foto ke Sistem PPDB

## 📁 Lokasi File Gambar
Semua foto disimpan di folder: `public/images/`

## 🖼️ Jenis Foto yang Bisa Ditambahkan

### 1. **Foto Sekolah Utama**
- **File**: `public/images/sekolah.jpg`
- **Ukuran**: 800x800px (persegi)
- **Format**: JPG, PNG
- **Lokasi**: Welcome page (hero section)

### 2. **Logo Sekolah**
- **File**: `public/images/logo.png`
- **Ukuran**: 200x200px (persegi)
- **Format**: PNG (dengan background transparan)
- **Lokasi**: Navigation bar

### 3. **Foto Fasilitas**
- **File**: `public/images/fasilitas/`
  - `lab-komputer.jpg`
  - `ruang-kelas.jpg`
  - `perpustakaan.jpg`
  - `workshop.jpg`
- **Ukuran**: 600x400px
- **Format**: JPG, PNG

### 4. **Foto Jurusan**
- **File**: `public/images/jurusan/`
  - `pplg.jpg` (Pengembangan Perangkat Lunak dan Gim)
  - `akt.jpg` (Akuntansi)
  - `anm.jpg` (Animasi)
  - `dkv.jpg` (Desain Komunikasi Visual)
  - `pms.jpg` (Pemasaran)
- **Ukuran**: 400x300px
- **Format**: JPG, PNG

### 5. **Foto Profil Default**
- **File**: `public/images/profile-default.jpg`
- **Ukuran**: 300x300px (persegi)
- **Format**: JPG, PNG

## 🔧 Cara Menambahkan Foto

### Langkah 1: Siapkan Foto
1. Pastikan foto berkualitas baik (tidak blur)
2. Resize foto sesuai ukuran yang direkomendasikan
3. Kompres foto agar ukuran file tidak terlalu besar (max 2MB)

### Langkah 2: Upload Foto
1. Buka folder `public/images/`
2. Copy paste foto ke folder yang sesuai
3. Pastikan nama file sesuai dengan yang tertera di panduan

### Langkah 3: Update Kode (Opsional)
Jika ingin menggunakan foto, ganti kode berikut:

**Di Welcome Page:**
```html
<!-- Ganti ini -->
<div class="text-center text-white">
    <div class="text-6xl mb-4">🏫</div>
    <p class="text-lg font-semibold">Gedung Sekolah</p>
</div>

<!-- Dengan ini -->
<img src="/images/sekolah.jpg" alt="SMK Bakti Nusantara 666" class="w-full h-full object-cover rounded-2xl">
```

**Di Dashboard:**
```html
<!-- Ganti ini -->
<div class="text-center text-white">
    <div class="text-6xl mb-4">📸</div>
    <p class="text-sm opacity-75">Foto Profil</p>
</div>

<!-- Dengan ini -->
<img src="/images/profile-default.jpg" alt="Profile" class="w-full h-full object-cover rounded-3xl">
```

## 📋 Checklist Foto yang Dibutuhkan

- [ ] `sekolah.jpg` - Foto gedung sekolah utama
- [ ] `logo.png` - Logo sekolah
- [ ] `fasilitas/lab-komputer.jpg` - Lab komputer
- [ ] `fasilitas/ruang-kelas.jpg` - Ruang kelas
- [ ] `fasilitas/perpustakaan.jpg` - Perpustakaan
- [ ] `jurusan/pplg.jpg` - Foto kegiatan PPLG
- [ ] `jurusan/akt.jpg` - Foto kegiatan Akuntansi
- [ ] `jurusan/anm.jpg` - Foto kegiatan Animasi
- [ ] `jurusan/dkv.jpg` - Foto kegiatan DKV
- [ ] `jurusan/pms.jpg` - Foto kegiatan Pemasaran
- [ ] `profile-default.jpg` - Foto profil default

## 💡 Tips Foto yang Bagus

1. **Pencahayaan**: Gunakan cahaya alami atau pencahayaan yang cukup
2. **Komposisi**: Pastikan objek utama berada di tengah atau mengikuti rule of thirds
3. **Kualitas**: Gunakan kamera dengan resolusi minimal 5MP
4. **Background**: Pilih background yang bersih dan tidak mengganggu
5. **Format**: Gunakan JPG untuk foto biasa, PNG untuk logo dengan background transparan

## 🚀 Setelah Upload Foto

1. Refresh browser untuk melihat perubahan
2. Cek apakah foto tampil dengan baik di semua device (mobile, tablet, desktop)
3. Pastikan loading time website tidak terlalu lambat

## ❓ Troubleshooting

**Foto tidak muncul?**
- Cek nama file apakah sudah benar
- Pastikan foto ada di folder yang tepat
- Cek permission folder (harus readable)

**Foto terlalu besar?**
- Kompres foto menggunakan tools online
- Resize foto sesuai ukuran yang direkomendasikan

**Foto blur atau pecah?**
- Gunakan foto dengan resolusi lebih tinggi
- Pastikan foto tidak di-stretch terlalu besar