-- Script SQL untuk memperbaiki semua role login
-- Jalankan di phpMyAdmin atau MySQL client

USE ppdb;

-- 1. Update role yang tidak konsisten
UPDATE users SET role = 'verifikator' WHERE role = 'verifikator_adm';

-- 2. Hapus user duplikat jika ada
DELETE u1 FROM users u1
INNER JOIN users u2 
WHERE u1.id > u2.id 
AND u1.email = u2.email;

-- 3. Insert/Update semua user admin dengan password yang benar
-- Password hash untuk Laravel (bcrypt)

-- Admin
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Administrator', 
    'admin@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'admin',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    name = 'Administrator',
    role = 'admin',
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    updated_at = NOW();

-- Kepala Sekolah
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Kepala Sekolah', 
    'kepsek@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'kepsek',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    name = 'Kepala Sekolah',
    role = 'kepsek',
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    updated_at = NOW();

-- Bagian Keuangan
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Bagian Keuangan', 
    'keuangan@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'keuangan',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    name = 'Bagian Keuangan',
    role = 'keuangan',
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    updated_at = NOW();

-- Verifikator Administrasi
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Verifikator Administrasi', 
    'verifikator@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'verifikator',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    name = 'Verifikator Administrasi',
    role = 'verifikator',
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    updated_at = NOW();

-- 4. Tampilkan hasil
SELECT id, name, email, role, created_at FROM users 
WHERE role IN ('admin', 'verifikator', 'kepsek', 'keuangan') 
ORDER BY role;