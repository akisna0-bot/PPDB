-- Database PPDB SMK Bakti Nusantara 666
DROP DATABASE IF EXISTS ppdb;
CREATE DATABASE ppdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ppdb;

-- Referensi & Master
CREATE TABLE jurusan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kuota INT NOT NULL
);

CREATE TABLE gelombang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    tahun INT NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    biaya_daftar DECIMAL(12,2) NOT NULL
);

CREATE TABLE wilayah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provinsi VARCHAR(100) NOT NULL,
    kabupaten VARCHAR(100) NOT NULL,
    kecamatan VARCHAR(100) NOT NULL,
    kelurahan VARCHAR(100) NOT NULL,
    kodepos VARCHAR(10),
    INDEX idx_wilayah (kecamatan, kelurahan)
);

CREATE TABLE pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    hp VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('pendaftar','admin','verifikator_adm','keuangan','kepsek') NOT NULL,
    aktif TINYINT DEFAULT 1,
    INDEX idx_role (role)
);

-- Pendaftaran Data Siswa dan Transaksi
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
    FOREIGN KEY (user_id) REFERENCES pengguna(id),
    FOREIGN KEY (gelombang_id) REFERENCES gelombang(id),
    FOREIGN KEY (jurusan_id) REFERENCES jurusan(id),
    INDEX idx_status (status)
);

CREATE TABLE pendaftar_data_siswa (
    pendaftar_id BIGINT PRIMARY KEY,
    nik VARCHAR(20) NOT NULL,
    nisn VARCHAR(20),
    nama VARCHAR(120) NOT NULL,
    jk ENUM('L','P') NOT NULL,
    tmp_lahir VARCHAR(60) NOT NULL,
    tgl_lahir DATE NOT NULL,
    alamat TEXT NOT NULL,
    wilayah_id INT,
    lat DECIMAL(10,7),
    lng DECIMAL(10,7),
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id),
    FOREIGN KEY (wilayah_id) REFERENCES wilayah(id)
);

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
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id)
);

CREATE TABLE pendaftar_asal_sekolah (
    pendaftar_id BIGINT PRIMARY KEY,
    npsn VARCHAR(20),
    nama_sekolah VARCHAR(150) NOT NULL,
    kabupaten VARCHAR(100),
    nilai_rata DECIMAL(5,2),
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id)
);

CREATE TABLE pendaftar_berkas (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    pendaftar_id BIGINT NOT NULL,
    jenis ENUM('IJAZAH','RAPOR','KIP','KKS','AKTA','KK','LAINNYA') NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    ukuran_kb INT,
    valid TINYINT DEFAULT 0,
    catatan VARCHAR(255),
    FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id),
    INDEX idx_pendaftar_jenis (pendaftar_id, jenis)
);

CREATE TABLE log_aktivitas (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    aksi VARCHAR(100) NOT NULL,
    objek VARCHAR(100),
    objek_data JSON,
    waktu DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45),
    FOREIGN KEY (user_id) REFERENCES pengguna(id),
    INDEX idx_user_waktu (user_id, waktu)
);

-- 3. Insert Data Sample
-- Insert jurusan
INSERT INTO jurusan (kode, nama, kuota) VALUES
('TKJ', 'Teknik Komputer dan Jaringan', 36),
('RPL', 'Rekayasa Perangkat Lunak', 36),
('MM', 'Multimedia', 36),
('OTKP', 'Otomatisasi dan Tata Kelola Perkantoran', 36);

-- Insert gelombang
INSERT INTO gelombang (nama, tahun, tgl_mulai, tgl_selesai, biaya_daftar) VALUES
('Gelombang 1', 2025, '2025-01-01', '2025-03-31', 150000.00),
('Gelombang 2', 2025, '2025-04-01', '2025-06-30', 200000.00);

-- Insert wilayah sample
INSERT INTO wilayah (provinsi, kabupaten, kecamatan, kelurahan, kodepos) VALUES
('Jawa Barat', 'Bandung', 'Coblong', 'Dago', '40135'),
('Jawa Barat', 'Bandung', 'Sukasari', 'Geger Kalong', '40153'),
('Jawa Barat', 'Bandung', 'Cidadap', 'Hegarmanah', '40141'),
('Jawa Barat', 'Cimahi', 'Cimahi Utara', 'Citeureup', '40512'),
('Jawa Barat', 'Cimahi', 'Cimahi Tengah', 'Baros', '40521');

-- Insert pengguna
INSERT INTO pengguna (nama, email, password_hash, role, aktif) VALUES
('Admin SMK', 'admin@smk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('Verifikator Administrasi', 'verifikator@smk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'verifikator_adm', 1),
('Staff Keuangan', 'keuangan@smk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'keuangan', 1),
('Kepala Sekolah', 'kepsek@smk.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kepsek', 1),
('Siswa Test', 'siswa@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pendaftar', 1);

-- Insert sample pendaftar
INSERT INTO pendaftar (user_id, no_pendaftaran, gelombang_id, jurusan_id, status) VALUES
(5, 'PPDB2025001', 1, 1, 'SUBMIT');

-- Insert sample data siswa
INSERT INTO pendaftar_data_siswa (pendaftar_id, nik, nisn, nama, jk, tmp_lahir, tgl_lahir, alamat, wilayah_id, lat, lng) VALUES
(1, '3273010101050001', '0012345678', 'Siswa Test', 'L', 'Bandung', '2005-01-01', 'Jl. Test No. 123', 1, -6.8951, 107.6084);

-- Insert sample data ortu
INSERT INTO pendaftar_data_ortu (pendaftar_id, nama_ayah, pekerjaan_ayah, hp_ayah, nama_ibu, pekerjaan_ibu, hp_ibu) VALUES
(1, 'Ayah Test', 'Wiraswasta', '081234567890', 'Ibu Test', 'Ibu Rumah Tangga', '081234567891');

-- Insert sample asal sekolah
INSERT INTO pendaftar_asal_sekolah (pendaftar_id, npsn, nama_sekolah, kabupaten, nilai_rata) VALUES
(1, '20219999', 'SMP Negeri 1 Test', 'Bandung', 85.50);

COMMIT;