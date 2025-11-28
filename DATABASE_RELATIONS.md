# RELASI DATABASE PPDB

## Struktur Relasi Antar Tabel

### 1. **pengguna** (User Model)
- **Primary Key**: `id`
- **Relasi Keluar**:
  - `hasMany` → **pendaftar** (user_id)
  - `hasMany` → **log_aktivitas** (user_id)

### 2. **pendaftar** (Pendaftar Model) - TABEL UTAMA
- **Primary Key**: `id`
- **Foreign Keys**:
  - `user_id` → **pengguna**(id)
  - `gelombang_id` → **gelombang**(id)
  - `jurusan_id` → **jurusan**(id)
- **Relasi Masuk**:
  - `belongsTo` → **pengguna** (user_id)
  - `belongsTo` → **gelombang** (gelombang_id)
  - `belongsTo` → **jurusan** (jurusan_id)
- **Relasi Keluar**:
  - `hasOne` → **pendaftar_data_siswa** (pendaftar_id)
  - `hasOne` → **pendaftar_data_ortu** (pendaftar_id)
  - `hasOne` → **pendaftar_asal_sekolah** (pendaftar_id)
  - `hasMany` → **pendaftar_berkas** (pendaftar_id)

### 3. **jurusan** (Jurusan Model)
- **Primary Key**: `id`
- **Relasi Keluar**:
  - `hasMany` → **pendaftar** (jurusan_id)

### 4. **gelombang** (Gelombang Model)
- **Primary Key**: `id`
- **Relasi Keluar**:
  - `hasMany` → **pendaftar** (gelombang_id)

### 5. **wilayah** (Wilayah Model)
- **Primary Key**: `id`
- **Relasi Keluar**:
  - `hasMany` → **pendaftar_data_siswa** (wilayah_id)

### 6. **pendaftar_data_siswa** (PendaftarDataSiswa Model)
- **Primary Key**: `pendaftar_id`
- **Foreign Keys**:
  - `pendaftar_id` → **pendaftar**(id)
  - `wilayah_id` → **wilayah**(id)
- **Relasi Masuk**:
  - `belongsTo` → **pendaftar** (pendaftar_id)
  - `belongsTo` → **wilayah** (wilayah_id)

### 7. **pendaftar_data_ortu** (PendaftarDataOrtu Model)
- **Primary Key**: `pendaftar_id`
- **Foreign Keys**:
  - `pendaftar_id` → **pendaftar**(id)
- **Relasi Masuk**:
  - `belongsTo` → **pendaftar** (pendaftar_id)

### 8. **pendaftar_asal_sekolah** (PendaftarAsalSekolah Model)
- **Primary Key**: `pendaftar_id`
- **Foreign Keys**:
  - `pendaftar_id` → **pendaftar**(id)
- **Relasi Masuk**:
  - `belongsTo` → **pendaftar** (pendaftar_id)

### 9. **pendaftar_berkas** (PendaftarBerkas Model)
- **Primary Key**: `id`
- **Foreign Keys**:
  - `pendaftar_id` → **pendaftar**(id)
- **Relasi Masuk**:
  - `belongsTo` → **pendaftar** (pendaftar_id)

### 10. **log_aktivitas** (LogAktivitas Model)
- **Primary Key**: `id`
- **Foreign Keys**:
  - `user_id` → **pengguna**(id)
- **Relasi Masuk**:
  - `belongsTo` → **pengguna** (user_id)

## Contoh Penggunaan Relasi

### 1. Mengambil Data Lengkap Pendaftar
```php
$pendaftar = Pendaftar::with([
    'user',
    'gelombang', 
    'jurusan',
    'dataSiswa.wilayah',
    'dataOrtu',
    'asalSekolah',
    'berkas'
])->find($id);

echo $pendaftar->user->nama;                    // Nama user
echo $pendaftar->gelombang->nama;               // Nama gelombang
echo $pendaftar->jurusan->nama;                 // Nama jurusan
echo $pendaftar->dataSiswa->nama;               // Nama siswa
echo $pendaftar->dataSiswa->wilayah->kecamatan; // Kecamatan
echo $pendaftar->dataOrtu->nama_ayah;           // Nama ayah
echo $pendaftar->asalSekolah->nama_sekolah;     // Asal sekolah
echo $pendaftar->berkas->count();               // Jumlah berkas
```

### 2. Mengambil Semua Pendaftar per Jurusan
```php
$jurusan = Jurusan::with('pendaftar.user')->find($id);
foreach($jurusan->pendaftar as $p) {
    echo $p->user->nama . " - " . $p->no_pendaftaran;
}
```

### 3. Mengambil Pendaftar per Gelombang
```php
$gelombang = Gelombang::with('pendaftar.jurusan')->find($id);
foreach($gelombang->pendaftar as $p) {
    echo $p->no_pendaftaran . " - " . $p->jurusan->nama;
}
```

### 4. Log Aktivitas User
```php
$user = User::with('logAktivitas')->find($id);
foreach($user->logAktivitas as $log) {
    echo $log->aksi . " pada " . $log->waktu;
}
```

## Status Flow Pendaftaran
1. **SUBMIT** → Pendaftar submit data
2. **ADM_PASS** → Lolos verifikasi administrasi
3. **ADM_REJECT** → Ditolak verifikasi administrasi  
4. **PAID** → Sudah bayar (diterima)

## Keamanan Relasi
- Semua foreign key constraint aktif
- Cascade delete untuk data terkait pendaftar
- Index pada kolom yang sering di-query
- Validasi role user sebelum akses data