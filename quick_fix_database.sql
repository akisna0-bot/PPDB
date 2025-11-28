-- Script cepat untuk memperbaiki user dan koneksi
-- Jalankan di phpMyAdmin

USE ppdb;

-- Buat user admin dengan password yang benar
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES (
    'Administrator', 
    'admin@ppdb.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'admin',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE 
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    role = 'admin';

-- Tampilkan user yang berhasil dibuat
SELECT name, email, role FROM users WHERE email = 'admin@ppdb.com';