-- Script SQL untuk memperbaiki login verifikator
-- Jalankan script ini di phpMyAdmin atau MySQL client

USE ppdb;

-- 1. Update role verifikator_adm menjadi verifikator
UPDATE users SET role = 'verifikator' WHERE role = 'verifikator_adm';

-- 2. Pastikan user verifikator ada dengan password yang benar
-- Password hash untuk 'verifikator123'
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Verifikator Administrasi', 
    'verifikator@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'verifikator',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    role = 'verifikator',
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

-- 3. Tampilkan semua user admin
SELECT id, name, email, role FROM users WHERE role IN ('admin', 'verifikator', 'kepsek', 'keuangan');