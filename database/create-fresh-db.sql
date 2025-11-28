-- Fresh Database Creation for PPDB
-- Run this to create a clean database

DROP DATABASE IF EXISTS `ppdb`;
CREATE DATABASE `ppdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ppdb`;

-- Users table
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('applicant','admin','verifikator','keuangan','kepsek') NOT NULL DEFAULT 'applicant',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Majors table
CREATE TABLE `majors` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `kuota` int(11) NOT NULL DEFAULT 36,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Waves table
CREATE TABLE `waves` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `biaya_daftar` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Applicants table
CREATE TABLE `applicants` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `no_pendaftaran` varchar(20) NOT NULL,
  `major_id` bigint(20) UNSIGNED DEFAULT NULL,
  `wave_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `asal_sekolah` varchar(255) DEFAULT NULL,
  `npsn_asal` varchar(8) DEFAULT NULL,
  `alamat_sekolah_asal` text DEFAULT NULL,
  `tahun_lulus` year(4) DEFAULT NULL,
  `nama_ayah` varchar(255) DEFAULT NULL,
  `pekerjaan_ayah` varchar(100) DEFAULT NULL,
  `penghasilan_ayah` varchar(50) DEFAULT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `pekerjaan_ibu` varchar(100) DEFAULT NULL,
  `penghasilan_ibu` varchar(50) DEFAULT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `hubungan_wali` varchar(50) DEFAULT NULL,
  `no_hp_ortu` varchar(15) DEFAULT NULL,
  `status` enum('DRAFT','SUBMIT','VERIFIED','REJECTED') NOT NULL DEFAULT 'DRAFT',
  `catatan_verifikasi` text DEFAULT NULL,
  `user_verifikasi_adm` varchar(255) DEFAULT NULL,
  `tgl_verifikasi_adm` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `applicants_no_pendaftaran_unique` (`no_pendaftaran`),
  KEY `applicants_user_id_foreign` (`user_id`),
  KEY `applicants_major_id_foreign` (`major_id`),
  KEY `applicants_wave_id_foreign` (`wave_id`),
  CONSTRAINT `applicants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `applicants_major_id_foreign` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `applicants_wave_id_foreign` FOREIGN KEY (`wave_id`) REFERENCES `waves` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `majors` (`name`, `code`, `kuota`, `description`) VALUES
('Pengembangan Perangkat Lunak dan Gim', 'PPLG', 72, 'Program keahlian pengembangan software dan game'),
('Akuntansi dan Keuangan Lembaga', 'AKT', 72, 'Program keahlian akuntansi dan keuangan'),
('Animasi', 'ANM', 36, 'Program keahlian animasi dan multimedia'),
('Desain Komunikasi Visual', 'DKV', 36, 'Program keahlian desain grafis dan visual'),
('Pemasaran', 'PMS', 72, 'Program keahlian pemasaran dan bisnis');

INSERT INTO `waves` (`name`, `tgl_mulai`, `tgl_selesai`, `biaya_daftar`, `is_active`) VALUES
('Gelombang 1', '2025-01-01', '2025-03-31', 150000.00, 1),
('Gelombang 2', '2025-04-01', '2025-06-30', 200000.00, 1);

INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin PPDB', 'admin@smkbn666.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Verifikator', 'verifikator@smkbn666.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'verifikator'),
('Keuangan', 'keuangan@smkbn666.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'keuangan'),
('Kepala Sekolah', 'kepsek@smkbn666.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kepsek');