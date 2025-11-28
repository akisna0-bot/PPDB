-- =====================================================
-- DATABASE DESIGN PPDB - STRUKTUR LENGKAP
-- =====================================================

DROP DATABASE IF EXISTS ppdb_complete;
CREATE DATABASE ppdb_complete CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ppdb_complete;

-- =====================================================
-- TABEL REFERENSI & MASTER
-- =====================================================

-- Tabel Jurusan
CREATE TABLE jurusan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kuota INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_kode (kode)
);

-- Tabel Gelombang
CREATE TABLE gelombang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    tahun INT NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    biaya_daftar DECIMAL(12,2) NOT NULL,
    aktif TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tahun (tahun),
    INDEX idx_aktif (aktif)
);

-- Tabel Wilayah
CREATE TABLE wilayah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provinsi VARCHAR(100) NOT NULL,
    kabupaten VARCHAR(100) NOT NULL,
    kecamatan VARCHAR(100) NOT NULL,
    kelurahan VARCHAR(100) NOT NULL,
    kodepos VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_kecamatan_kelurahan (kecamatan, kelurahan),
    INDEX idx_kabupaten (kabupaten)
);

-- Tabel Pengguna
CREATE TABLE pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    hp VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('pendaftar','admin','verifikator_adm','keuangan','kepsek') NOT NULL,
    aktif TINYINT DEFAULT 1,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_aktif (aktif)
);

-- =====================================================
-- TABEL PENDAFTARAN DATA SISWA DAN TRANSAKSI
-- =====================================================

-- Tabel Pendaftar (Induk)
CREATE TABLE pendaftar (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
    no_pendaftaran VARCHAR(20) UNIQUE NOT NULL,
    gelombang_id INT NOT NULL,
    jurusan_id INT NOT NULL,
    status ENUM('SUBMIT','ADM_PASS','ADM_REJECT','PAID') DEFAULT 'SUBMIT',
    user_verifikasi_adm VARCHAR(100),
    tgl_verifikasi_adm DATETIME,
    user_verifikasi_payment VARCHAR(100),
    tgl_verifikasi_payment DATETIME,
    catatan_verifikasi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES pengguna(id) ON DELETE CASCADE,
    FOREIGN KEY (gelombang_id) REFERENCES gelombang(id),
    FOREIGN KEY (jurusan_id) REFERENCES jurusan(id),
    
    INDEX idx_no_pendaftaran (no_pendaftaran),
    INDEX idx_status (status),
    INDEX idx_tanggal_daftar (tanggal_daftar),
    INDEX idx_gelombang (gelombang_id),
    INDEX idx_jurusan (jurusan_id)
);

-- Tabel Data Siswa
CREATE TABLE pendaftar_data_siswa (
    pendaftar_id BIGINT PRIMARY KEY,
    nik VARCHAR(20) UNIQUE,
    nisn VARCHAR(20),
    nama VARCHAR(120) NOT NULL,
    jk ENUM('L','P') NOT NULL,
    tmp_lahir VARCHAR(60),
    tgl_lahir DATE,
    alamat TEXT,
    wilayah_id INT,
    lat DECIMAL(10,7),
    lng DECIMAL(10,7),
    agama VARCHAR(20),
    no_hp VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id) ON DELETE CASCADE,
    FOREIGN KEY (wilayah_id) REFERENCES wilayah(id),
    
    INDEX idx_nik (nik),
    INDEX idx_nisn (nisn),
    INDEX idx_jk (jk),
    INDEX idx_wilayah (wilayah_id)
);

-- Tabel Data Orang Tua/Wali
CREATE TABLE pendaftar_data_ortu (
    pendaftar_id BIGINT PRIMARY KEY,
    nama_ayah VARCHAR(120),
    pekerjaan_ayah VARCHAR(100),
    hp_ayah VARCHAR(20),
    nama_ibu VARCHAR(120),
    pekerjaan_ibu VARCHAR(100),
    hp_ibu VARCHAR(20),
    wali_nama VARCHAR(120),
    wali_hp VARCHAR(20),
    penghasilan_ortu DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id) ON DELETE CASCADE
);

-- Tabel Asal Sekolah
CREATE TABLE pendaftar_asal_sekolah (
    pendaftar_id BIGINT PRIMARY KEY,
    npsn VARCHAR(20),
    nama_sekolah VARCHAR(150) NOT NULL,
    kabupaten VARCHAR(100),
    nilai_rata DECIMAL(5,2),
    tahun_lulus YEAR,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id) ON DELETE CASCADE,
    
    INDEX idx_npsn (npsn),
    INDEX idx_kabupaten (kabupaten),
    INDEX idx_tahun_lulus (tahun_lulus)
);

-- Tabel Berkas
CREATE TABLE pendaftar_berkas (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    pendaftar_id BIGINT NOT NULL,
    jenis ENUM('IJAZAH','RAPOR','KIP','KKS','AKTA','KK','LAINNYA') NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    ukuran_kb INT,
    valid TINYINT DEFAULT 0,
    catatan VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified_at TIMESTAMP NULL,
    verified_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id) ON DELETE CASCADE,
    
    INDEX idx_pendaftar (pendaftar_id),
    INDEX idx_jenis (jenis),
    INDEX idx_valid (valid)
);

-- =====================================================
-- TABEL PEMBAYARAN
-- =====================================================

CREATE TABLE pembayaran (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    pendaftar_id BIGINT NOT NULL,
    kode_pembayaran VARCHAR(50) UNIQUE NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    metode ENUM('TRANSFER','CASH','ONLINE') DEFAULT 'TRANSFER',
    status ENUM('PENDING','PAID','VERIFIED','FAILED') DEFAULT 'PENDING',
    bukti_pembayaran VARCHAR(255),
    tanggal_bayar DATETIME,
    tanggal_verifikasi DATETIME,
    verifikator VARCHAR(100),
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id) ON DELETE CASCADE,
    
    INDEX idx_kode (kode_pembayaran),
    INDEX idx_status (status),
    INDEX idx_pendaftar (pendaftar_id)
);

-- =====================================================
-- TABEL LOG & NOTIFIKASI
-- =====================================================

CREATE TABLE log_aktivitas (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    pendaftar_id BIGINT,
    aktivitas VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES pengguna(id) ON DELETE SET NULL,
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id) ON DELETE SET NULL,
    
    INDEX idx_user (user_id),
    INDEX idx_pendaftar (pendaftar_id),
    INDEX idx_aktivitas (aktivitas),
    INDEX idx_created_at (created_at)
);

CREATE TABLE notifikasi (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    pesan TEXT NOT NULL,
    tipe ENUM('INFO','SUCCESS','WARNING','ERROR') DEFAULT 'INFO',
    dibaca TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES pengguna(id) ON DELETE CASCADE,
    
    INDEX idx_user (user_id),
    INDEX idx_dibaca (dibaca),
    INDEX idx_tipe (tipe)
);

-- =====================================================
-- INSERT DATA SAMPLE
-- =====================================================

-- Insert Jurusan
INSERT INTO jurusan (kode, nama, kuota) VALUES
('PPLG', 'Pengembangan Perangkat Lunak dan Gim', 72),
('AKT', 'Akuntansi', 72),
('ANM', 'Animasi', 36),
('DKV', 'Desain Komunikasi Visual', 36),
('PMS', 'Pemasaran', 72);

-- Insert Gelombang
INSERT INTO gelombang (nama, tahun, tgl_mulai, tgl_selesai, biaya_daftar, aktif) VALUES
('Gelombang 1', 2025, '2025-01-01', '2025-03-31', 150000.00, 1),
('Gelombang 2', 2025, '2025-04-01', '2025-06-30', 200000.00, 0);

-- Insert Wilayah Sample
INSERT INTO wilayah (provinsi, kabupaten, kecamatan, kelurahan, kodepos) VALUES
('Jawa Barat', 'Bandung', 'Cileunyi', 'Cileunyi Kulon', '40622'),
('Jawa Barat', 'Bandung', 'Cileunyi', 'Cileunyi Wetan', '40622'),
('Jawa Barat', 'Bandung', 'Rancaekek', 'Rancaekek Kulon', '40394');

-- Insert Users
INSERT INTO pengguna (nama, email, password_hash, role) VALUES
('Administrator', 'admin@ppdb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Verifikator', 'verifikator@ppdb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'verifikator_adm'),
('Keuangan', 'keuangan@ppdb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'keuangan'),
('Kepala Sekolah', 'kepsek@ppdb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kepsek');

-- =====================================================
-- VIEWS UNTUK LAPORAN
-- =====================================================

-- View Ringkasan Pendaftar
CREATE VIEW v_ringkasan_pendaftar AS
SELECT 
    p.id,
    p.no_pendaftaran,
    p.tanggal_daftar,
    p.status,
    u.nama as nama_pendaftar,
    u.email,
    j.nama as jurusan,
    g.nama as gelombang,
    ds.nama as nama_lengkap,
    ds.jk,
    w.kabupaten,
    w.kecamatan
FROM pendaftar p
JOIN pengguna u ON p.user_id = u.id
JOIN jurusan j ON p.jurusan_id = j.id
JOIN gelombang g ON p.gelombang_id = g.id
LEFT JOIN pendaftar_data_siswa ds ON p.id = ds.pendaftar_id
LEFT JOIN wilayah w ON ds.wilayah_id = w.id;

-- View Statistik per Jurusan
CREATE VIEW v_statistik_jurusan AS
SELECT 
    j.kode,
    j.nama,
    j.kuota,
    COUNT(p.id) as total_pendaftar,
    COUNT(CASE WHEN p.status = 'ADM_PASS' THEN 1 END) as diterima,
    COUNT(CASE WHEN p.status = 'SUBMIT' THEN 1 END) as menunggu,
    COUNT(CASE WHEN p.status = 'ADM_REJECT' THEN 1 END) as ditolak
FROM jurusan j
LEFT JOIN pendaftar p ON j.id = p.jurusan_id
GROUP BY j.id, j.kode, j.nama, j.kuota;

-- =====================================================
-- SELESAI
-- =====================================================

SHOW TABLES;
SELECT 'Database PPDB Complete berhasil dibuat!' as status;