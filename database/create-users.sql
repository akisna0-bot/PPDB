-- Hapus user lama jika ada
DELETE FROM users WHERE email IN ('admin@smk.com', 'verifikator@smk.com', 'keuangan@smk.com', 'kepsek@smk.com');

-- Insert user baru dengan password yang sudah di-hash
INSERT INTO users (name, email, email_verified_at, password, role, created_at, updated_at) VALUES
('Admin SMK', 'admin@smk.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('Verifikator SMK', 'verifikator@smk.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'verifikator', NOW(), NOW()),
('Keuangan SMK', 'keuangan@smk.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'keuangan', NOW(), NOW()),
('Kepala Sekolah', 'kepsek@smk.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kepsek', NOW(), NOW());