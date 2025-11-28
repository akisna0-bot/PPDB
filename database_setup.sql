-- =====================================================
-- SETUP DATABASE PPDB (Penerimaan Peserta Didik Baru)
-- =====================================================

-- Buat database baru
CREATE DATABASE IF NOT EXISTS ppdb_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ppdb_db;

-- =====================================================
-- TABEL USERS (Pengguna Sistem)
-- =====================================================
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'verifikator_adm', 'keuangan', 'kepsek') DEFAULT 'user',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- =====================================================
-- TABEL MAJORS (Jurusan)
-- =====================================================
CREATE TABLE majors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    kuota INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- =====================================================
-- TABEL WAVES (Gelombang Pendaftaran)
-- =====================================================
CREATE TABLE waves (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- =====================================================
-- TABEL APPLICANTS (Data Pendaftar)
-- =====================================================
CREATE TABLE applicants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    no_pendaftaran VARCHAR(20) UNIQUE NULL,
    wave_id BIGINT UNSIGNED NOT NULL,
    major_id BIGINT UNSIGNED NOT NULL,
    
    -- Data Pribadi
    nama_lengkap VARCHAR(255) NULL,
    nik VARCHAR(16) NULL,
    tempat_lahir VARCHAR(100) NULL,
    tanggal_lahir DATE NULL,
    jenis_kelamin ENUM('L', 'P') NULL,
    agama VARCHAR(50) NULL,
    no_hp VARCHAR(15) NULL,
    
    -- Alamat
    alamat_lengkap TEXT NULL,
    rt VARCHAR(5) NULL,
    rw VARCHAR(5) NULL,
    kelurahan VARCHAR(100) NULL,
    kecamatan VARCHAR(100) NULL,
    kabupaten VARCHAR(100) NULL,
    provinsi VARCHAR(100) NULL,
    kode_pos VARCHAR(10) NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    
    -- Data Sekolah
    asal_sekolah VARCHAR(255) NULL,
    npsn VARCHAR(20) NULL,
    tahun_lulus YEAR NULL,
    
    -- Status dan Verifikasi
    status ENUM('DRAFT', 'SUBMIT', 'VERIFIED', 'REJECTED', 'submitted', 'verified', 'rejected', 'need_revision') DEFAULT 'DRAFT',
    catatan_verifikasi TEXT NULL,
    user_verifikasi_adm VARCHAR(255) NULL,
    tgl_verifikasi_adm TIMESTAMP NULL,
    
    -- Data Lengkap Flag
    is_data_complete BOOLEAN DEFAULT FALSE,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (wave_id) REFERENCES waves(id),
    FOREIGN KEY (major_id) REFERENCES majors(id)
);

-- =====================================================
-- TABEL APPLICANT_FILES (Berkas Pendaftar)
-- =====================================================
CREATE TABLE applicant_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    applicant_id BIGINT UNSIGNED NOT NULL,
    document_type ENUM('ktp', 'ijazah', 'transkrip', 'foto', 'surat_keterangan', 'kartu_keluarga', 'akta_lahir', 'sertifikat') NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT NULL,
    mime_type VARCHAR(100) NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    catatan VARCHAR(500) NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified_at TIMESTAMP NULL,
    verified_by VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE
);

-- =====================================================
-- TABEL PAYMENTS (Pembayaran)
-- =====================================================
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    applicant_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('transfer', 'cash', 'online') DEFAULT 'transfer',
    status ENUM('pending', 'paid', 'verified', 'failed') DEFAULT 'pending',
    payment_code VARCHAR(50) UNIQUE NULL,
    payment_proof VARCHAR(500) NULL,
    paid_at TIMESTAMP NULL,
    verified_at TIMESTAMP NULL,
    verified_by VARCHAR(255) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE
);

-- =====================================================
-- TABEL NOTIFICATIONS (Notifikasi)
-- =====================================================
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =====================================================
-- TABEL LOG_AKTIVITAS (Log Aktivitas Sistem)
-- =====================================================
CREATE TABLE log_aktivitas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    activity VARCHAR(255) NOT NULL,
    description TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- =====================================================
-- INSERT DATA AWAL
-- =====================================================

-- Insert Admin Default
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Administrator', 'admin@ppdb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('Verifikator', 'verifikator@ppdb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'verifikator_adm', NOW(), NOW()),
('Keuangan', 'keuangan@ppdb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'keuangan', NOW(), NOW()),
('Kepala Sekolah', 'kepsek@ppdb.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kepsek', NOW(), NOW());

-- Insert Jurusan
INSERT INTO majors (code, name, kuota, created_at, updated_at) VALUES
('TKJ', 'Teknik Komputer dan Jaringan', 36, NOW(), NOW()),
('RPL', 'Rekayasa Perangkat Lunak', 36, NOW(), NOW()),
('MM', 'Multimedia', 36, NOW(), NOW()),
('TKR', 'Teknik Kendaraan Ringan', 36, NOW(), NOW()),
('TSM', 'Teknik Sepeda Motor', 36, NOW(), NOW()),
('AKL', 'Akuntansi dan Keuangan Lembaga', 36, NOW(), NOW());

-- Insert Gelombang Pendaftaran
INSERT INTO waves (name, start_date, end_date, is_active, created_at, updated_at) VALUES
('Gelombang 1', '2024-01-01', '2024-03-31', TRUE, NOW(), NOW()),
('Gelombang 2', '2024-04-01', '2024-06-30', FALSE, NOW(), NOW()),
('Gelombang 3', '2024-07-01', '2024-09-30', FALSE, NOW(), NOW());

-- =====================================================
-- INDEXES UNTUK PERFORMA
-- =====================================================
CREATE INDEX idx_applicants_status ON applicants(status);
CREATE INDEX idx_applicants_major ON applicants(major_id);
CREATE INDEX idx_applicants_wave ON applicants(wave_id);
CREATE INDEX idx_applicants_no_pendaftaran ON applicants(no_pendaftaran);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_files_status ON applicant_files(status);
CREATE INDEX idx_notifications_user ON notifications(user_id, is_read);

-- =====================================================
-- SELESAI
-- =====================================================