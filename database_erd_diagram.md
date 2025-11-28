# 📊 DATABASE ERD DIAGRAM - PPDB SYSTEM

## 🗂️ **STRUKTUR TABEL & RELASI**

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                           DATABASE PPDB - ERD DIAGRAM                           │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│    PENGGUNA     │    │    GELOMBANG    │    │    JURUSAN      │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ 🔑 id (PK)      │    │ 🔑 id (PK)      │    │ 🔑 id (PK)      │
│ nama            │    │ nama            │    │ kode (UQ)       │
│ email (UQ)      │    │ tahun           │    │ nama            │
│ hp              │    │ tgl_mulai       │    │ kuota           │
│ password_hash   │    │ tgl_selesai     │    │ created_at      │
│ role (ENUM)     │    │ biaya_daftar    │    │ updated_at      │
│ aktif           │    │ aktif           │    └─────────────────┘
│ created_at      │    │ created_at      │              │
│ updated_at      │    │ updated_at      │              │
└─────────────────┘    └─────────────────┘              │
         │                       │                      │
         │                       │                      │
         │ 1:N                   │ 1:N                  │ 1:N
         │                       │                      │
         └───────────────────────┼──────────────────────┘
                                 │
                                 ▼
                    ┌─────────────────────────┐
                    │       PENDAFTAR         │
                    ├─────────────────────────┤
                    │ 🔑 id (PK)              │
                    │ 🔗 user_id (FK)         │
                    │ tanggal_daftar          │
                    │ no_pendaftaran (UQ)     │
                    │ 🔗 gelombang_id (FK)    │
                    │ 🔗 jurusan_id (FK)      │
                    │ status (ENUM)           │
                    │ user_verifikasi_adm     │
                    │ tgl_verifikasi_adm      │
                    │ user_verifikasi_payment │
                    │ tgl_verifikasi_payment  │
                    │ catatan_verifikasi      │
                    │ created_at              │
                    │ updated_at              │
                    └─────────────────────────┘
                                 │
                    ┌────────────┼────────────┐
                    │ 1:1        │ 1:1        │ 1:N
                    ▼            ▼            ▼
        ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
        │ PENDAFTAR_DATA_ │ │ PENDAFTAR_DATA_ │ │ PENDAFTAR_      │
        │     SISWA       │ │     ORTU        │ │    BERKAS       │
        ├─────────────────┤ ├─────────────────┤ ├─────────────────┤
        │🔑pendaftar_id(PK│ │🔑pendaftar_id(PK│ │ 🔑 id (PK)      │
        │🔗pendaftar_id(FK│ │🔗pendaftar_id(FK│ │🔗pendaftar_id(FK│
        │ nik (UQ)        │ │ nama_ayah       │ │ jenis (ENUM)    │
        │ nisn            │ │ pekerjaan_ayah  │ │ nama_file       │
        │ nama            │ │ hp_ayah         │ │ url             │
        │ jk (ENUM)       │ │ nama_ibu        │ │ ukuran_kb       │
        │ tmp_lahir       │ │ pekerjaan_ibu   │ │ valid           │
        │ tgl_lahir       │ │ hp_ibu          │ │ catatan         │
        │ alamat          │ │ wali_nama       │ │ uploaded_at     │
        │🔗wilayah_id(FK) │ │ wali_hp         │ │ verified_at     │
        │ lat             │ │ penghasilan_ortu│ │ verified_by     │
        │ lng             │ │ created_at      │ │ created_at      │
        │ agama           │ │ updated_at      │ │ updated_at      │
        │ no_hp           │ └─────────────────┘ └─────────────────┘
        │ created_at      │
        │ updated_at      │
        └─────────────────┘
                 │
                 │ N:1
                 ▼
        ┌─────────────────┐
        │    WILAYAH      │
        ├─────────────────┤
        │ 🔑 id (PK)      │
        │ provinsi        │
        │ kabupaten       │
        │ kecamatan       │
        │ kelurahan       │
        │ kodepos         │
        │ created_at      │
        │ updated_at      │
        └─────────────────┘

        ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
        │ PENDAFTAR_ASAL_ │ │   PEMBAYARAN    │ │ LOG_AKTIVITAS   │
        │    SEKOLAH      │ │                 │ │                 │
        ├─────────────────┤ ├─────────────────┤ ├─────────────────┤
        │🔑pendaftar_id(PK│ │ 🔑 id (PK)      │ │ 🔑 id (PK)      │
        │🔗pendaftar_id(FK│ │🔗pendaftar_id(FK│ │🔗user_id (FK)   │
        │ npsn            │ │ kode_pembayaran │ │🔗pendaftar_id(FK│
        │ nama_sekolah    │ │ jumlah          │ │ aktivitas       │
        │ kabupaten       │ │ metode (ENUM)   │ │ deskripsi       │
        │ nilai_rata      │ │ status (ENUM)   │ │ ip_address      │
        │ tahun_lulus     │ │ bukti_pembayaran│ │ user_agent      │
        │ created_at      │ │ tanggal_bayar   │ │ created_at      │
        │ updated_at      │ │ tanggal_verif   │ └─────────────────┘
        └─────────────────┘ │ verifikator     │
                           │ catatan         │ ┌─────────────────┐
                           │ created_at      │ │   NOTIFIKASI    │
                           │ updated_at      │ │                 │
                           └─────────────────┘ ├─────────────────┤
                                              │ 🔑 id (PK)      │
                                              │🔗user_id (FK)   │
                                              │ judul           │
                                              │ pesan           │
                                              │ tipe (ENUM)     │
                                              │ dibaca          │
                                              │ created_at      │
                                              │ updated_at      │
                                              └─────────────────┘
```

## 🔗 **RELASI ANTAR TABEL**

| Tabel Parent | Tabel Child | Relasi | Foreign Key |
|--------------|-------------|--------|-------------|
| **pengguna** | pendaftar | 1:N | user_id |
| **gelombang** | pendaftar | 1:N | gelombang_id |
| **jurusan** | pendaftar | 1:N | jurusan_id |
| **pendaftar** | pendaftar_data_siswa | 1:1 | pendaftar_id |
| **pendaftar** | pendaftar_data_ortu | 1:1 | pendaftar_id |
| **pendaftar** | pendaftar_asal_sekolah | 1:1 | pendaftar_id |
| **pendaftar** | pendaftar_berkas | 1:N | pendaftar_id |
| **pendaftar** | pembayaran | 1:N | pendaftar_id |
| **pendaftar** | log_aktivitas | 1:N | pendaftar_id |
| **wilayah** | pendaftar_data_siswa | 1:N | wilayah_id |
| **pengguna** | notifikasi | 1:N | user_id |
| **pengguna** | log_aktivitas | 1:N | user_id |

## 📋 **INDEX & CONSTRAINTS**

### Primary Keys (PK)
- Semua tabel memiliki PK auto increment
- Tabel junction menggunakan composite PK

### Unique Constraints (UQ)
- `pengguna.email`
- `jurusan.kode`
- `pendaftar.no_pendaftaran`
- `pendaftar_data_siswa.nik`
- `pembayaran.kode_pembayaran`

### Foreign Key Constraints (FK)
- Cascade DELETE untuk data terkait
- SET NULL untuk referensi opsional

### Indexes (IDX)
- Status, tanggal, wilayah untuk performa query
- Composite index untuk pencarian kompleks

## 🎯 **VIEWS UNTUK LAPORAN**

1. **v_ringkasan_pendaftar** - Data lengkap pendaftar
2. **v_statistik_jurusan** - Statistik per jurusan
3. **v_sebaran_wilayah** - Analisis geografis

---
**Database siap untuk implementasi dengan relasi yang optimal!** 🚀