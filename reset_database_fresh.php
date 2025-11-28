<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RESET DATABASE & CREATE FRESH SESUAI SPESIFIKASI ===\n\n";

try {
    // 1. Drop all existing tables
    echo "1. Dropping all existing tables...\n";
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    $tables = DB::select('SHOW TABLES');
    $dbName = env('DB_DATABASE');
    
    foreach ($tables as $table) {
        $tableName = $table->{"Tables_in_$dbName"};
        DB::statement("DROP TABLE IF EXISTS `$tableName`");
        echo "   - Dropped: $tableName\n";
    }
    
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    // 2. Create tables exactly as specification
    echo "\n2. Creating tables sesuai spesifikasi...\n";
    
    // Tabel jurusan
    DB::statement("
        CREATE TABLE jurusan (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kode VARCHAR(10) UNIQUE,
            nama VARCHAR(100),
            kuota INT
        )
    ");
    echo "   ✅ Created: jurusan\n";
    
    // Tabel gelombang  
    DB::statement("
        CREATE TABLE gelombang (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(50),
            tahun INT,
            tgl_mulai DATE,
            tgl_selesai DATE,
            biaya_daftar DECIMAL(12,2)
        )
    ");
    echo "   ✅ Created: gelombang\n";
    
    // Tabel wilayah
    DB::statement("
        CREATE TABLE wilayah (
            id INT AUTO_INCREMENT PRIMARY KEY,
            provinsi VARCHAR(100),
            kabupaten VARCHAR(100),
            kecamatan VARCHAR(100),
            kelurahan VARCHAR(100),
            kodepos VARCHAR(10),
            INDEX idx_kecamatan_kelurahan (kecamatan, kelurahan)
        )
    ");
    echo "   ✅ Created: wilayah\n";
    
    // Tabel pengguna
    DB::statement("
        CREATE TABLE pengguna (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(100),
            email VARCHAR(120) UNIQUE,
            hp VARCHAR(20),
            password_hash VARCHAR(255),
            role ENUM('pendaftar','admin','verifikator_adm','keuangan','kepsek'),
            aktif TINYINT,
            INDEX idx_role (role)
        )
    ");
    echo "   ✅ Created: pengguna\n";
    
    // Tabel pendaftar
    DB::statement("
        CREATE TABLE pendaftar (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            tanggal_daftar DATETIME,
            no_pendaftaran VARCHAR(20) UNIQUE,
            gelombang_id INT,
            jurusan_id INT,
            status ENUM('SUBMIT','ADM_PASS','ADM_REJECT','PAID'),
            user_verifikasi_adm VARCHAR(100),
            tgl_verifikasi_adm DATETIME,
            user_verifikasi_payment VARCHAR(100),
            tgl_verifikasi_payment DATETIME,
            FOREIGN KEY (user_id) REFERENCES pengguna(id),
            FOREIGN KEY (gelombang_id) REFERENCES gelombang(id),
            FOREIGN KEY (jurusan_id) REFERENCES jurusan(id),
            INDEX idx_status (status)
        )
    ");
    echo "   ✅ Created: pendaftar\n";
    
    // Tabel pendaftar_data_siswa
    DB::statement("
        CREATE TABLE pendaftar_data_siswa (
            pendaftar_id BIGINT PRIMARY KEY,
            nik VARCHAR(20),
            nisn VARCHAR(20),
            nama VARCHAR(120),
            jk ENUM('L','P'),
            tmp_lahir VARCHAR(60),
            tgl_lahir DATE,
            alamat TEXT,
            wilayah_id INT,
            lat DECIMAL(10,7),
            lng DECIMAL(10,7),
            FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id),
            FOREIGN KEY (wilayah_id) REFERENCES wilayah(id)
        )
    ");
    echo "   ✅ Created: pendaftar_data_siswa\n";
    
    // Tabel pendaftar_data_ortu
    DB::statement("
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
        )
    ");
    echo "   ✅ Created: pendaftar_data_ortu\n";
    
    // Tabel pendaftar_asal_sekolah
    DB::statement("
        CREATE TABLE pendaftar_asal_sekolah (
            pendaftar_id BIGINT PRIMARY KEY,
            npsn VARCHAR(20),
            nama_sekolah VARCHAR(150),
            kabupaten VARCHAR(100),
            nilai_rata DECIMAL(5,2),
            FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id)
        )
    ");
    echo "   ✅ Created: pendaftar_asal_sekolah\n";
    
    // Tabel pendaftar_berkas
    DB::statement("
        CREATE TABLE pendaftar_berkas (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            pendaftar_id BIGINT,
            jenis ENUM('IJAZAH','RAPOR','KIP','KKS','AKTA','KK','LAINNYA'),
            nama_file VARCHAR(255),
            url VARCHAR(255),
            ukuran_kb INT,
            valid TINYINT,
            catatan VARCHAR(255),
            FOREIGN KEY (pendaftar_id) REFERENCES pendaftar(id),
            INDEX idx_pendaftar_jenis (pendaftar_id, jenis)
        )
    ");
    echo "   ✅ Created: pendaftar_berkas\n";
    
    // Tabel log_aktivitas
    DB::statement("
        CREATE TABLE log_aktivitas (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            aksi VARCHAR(100),
            objek VARCHAR(100),
            objek_data JSON,
            waktu DATETIME,
            ip VARCHAR(45),
            FOREIGN KEY (user_id) REFERENCES pengguna(id),
            INDEX idx_user_waktu (user_id, waktu)
        )
    ");
    echo "   ✅ Created: log_aktivitas\n";
    
    // 3. Insert data awal
    echo "\n3. Inserting initial data...\n";
    
    // Data jurusan
    DB::table('jurusan')->insert([
        ['kode' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan', 'kuota' => 36],
        ['kode' => 'MM', 'nama' => 'Multimedia', 'kuota' => 36],
        ['kode' => 'AKL', 'nama' => 'Akuntansi dan Keuangan Lembaga', 'kuota' => 36]
    ]);
    echo "   ✅ Inserted jurusan data\n";
    
    // Data gelombang
    DB::table('gelombang')->insert([
        ['nama' => 'Gelombang 1', 'tahun' => 2025, 'tgl_mulai' => '2025-01-01', 'tgl_selesai' => '2025-03-31', 'biaya_daftar' => 150000.00],
        ['nama' => 'Gelombang 2', 'tahun' => 2025, 'tgl_mulai' => '2025-04-01', 'tgl_selesai' => '2025-06-30', 'biaya_daftar' => 200000.00]
    ]);
    echo "   ✅ Inserted gelombang data\n";
    
    // Data wilayah
    DB::table('wilayah')->insert([
        ['provinsi' => 'Jawa Barat', 'kabupaten' => 'Kabupaten Bandung', 'kecamatan' => 'Cileunyi', 'kelurahan' => 'Cileunyi Kulon', 'kodepos' => '40622'],
        ['provinsi' => 'Jawa Barat', 'kabupaten' => 'Kabupaten Bandung', 'kecamatan' => 'Cileunyi', 'kelurahan' => 'Cileunyi Wetan', 'kodepos' => '40623'],
        ['provinsi' => 'Jawa Barat', 'kabupaten' => 'Kabupaten Bandung', 'kecamatan' => 'Rancaekek', 'kelurahan' => 'Rancaekek Kulon', 'kodepos' => '40394']
    ]);
    echo "   ✅ Inserted wilayah data\n";
    
    // Data pengguna
    DB::table('pengguna')->insert([
        ['nama' => 'Administrator', 'email' => 'admin@ppdb.com', 'hp' => '081234567890', 'password_hash' => Hash::make('admin123'), 'role' => 'admin', 'aktif' => 1],
        ['nama' => 'Verifikator Administrasi', 'email' => 'verifikator@ppdb.com', 'hp' => '081234567891', 'password_hash' => Hash::make('verifikator123'), 'role' => 'verifikator_adm', 'aktif' => 1],
        ['nama' => 'Bagian Keuangan', 'email' => 'keuangan@ppdb.com', 'hp' => '081234567892', 'password_hash' => Hash::make('keuangan123'), 'role' => 'keuangan', 'aktif' => 1],
        ['nama' => 'Kepala Sekolah', 'email' => 'kepsek@ppdb.com', 'hp' => '081234567893', 'password_hash' => Hash::make('kepsek123'), 'role' => 'kepsek', 'aktif' => 1]
    ]);
    echo "   ✅ Inserted pengguna data\n";
    
    // 4. Verify tables
    echo "\n4. Verifying tables...\n";
    $tables = ['jurusan', 'gelombang', 'wilayah', 'pengguna', 'pendaftar', 'pendaftar_data_siswa', 'pendaftar_data_ortu', 'pendaftar_asal_sekolah', 'pendaftar_berkas', 'log_aktivitas'];
    
    foreach ($tables as $table) {
        $count = DB::table($table)->count();
        echo "   ✅ $table: $count records\n";
    }
    
    echo "\n=== DATABASE CREATED SUCCESSFULLY ===\n";
    echo "Structure is now EXACTLY as per specification!\n\n";
    
    echo "LOGIN CREDENTIALS:\n";
    echo "Admin: admin@ppdb.com / admin123\n";
    echo "Verifikator: verifikator@ppdb.com / verifikator123\n";
    echo "Keuangan: keuangan@ppdb.com / keuangan123\n";
    echo "Kepala Sekolah: kepsek@ppdb.com / kepsek123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}