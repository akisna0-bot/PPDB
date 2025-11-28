-- =====================================================
-- FIX STATUS ENUM UNTUK KONSISTENSI
-- =====================================================

USE ppdb_db;

-- Update data yang menggunakan status lama ke status baru
UPDATE applicants SET status = 'SUBMIT' WHERE status IN ('submitted', 'DRAFT');
UPDATE applicants SET status = 'VERIFIED' WHERE status IN ('verified', 'ADM_PASS');
UPDATE applicants SET status = 'REJECTED' WHERE status IN ('rejected', 'need_revision', 'ADM_REJECT');

-- Alter table untuk menggunakan enum yang konsisten
ALTER TABLE applicants MODIFY COLUMN status ENUM('SUBMIT', 'VERIFIED', 'REJECTED') DEFAULT 'SUBMIT';

-- Verifikasi perubahan
SELECT status, COUNT(*) as jumlah FROM applicants GROUP BY status;

-- =====================================================
-- SELESAI
-- =====================================================