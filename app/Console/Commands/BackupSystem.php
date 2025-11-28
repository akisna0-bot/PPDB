<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\LogAktivitas;

class BackupSystem extends Command
{
    protected $signature = 'ppdb:backup';
    protected $description = 'Backup otomatis database dan arsip laporan';

    public function handle()
    {
        $this->info('Memulai backup sistem...');
        
        // Backup database
        $this->backupDatabase();
        
        // Arsip laporan bulanan
        $this->archiveReports();
        
        // Cleanup old backups
        $this->cleanupOldBackups();
        
        $this->info('Backup sistem selesai.');
    }

    private function backupDatabase()
    {
        $filename = 'backup_ppdb_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // Ensure backup directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Get database configuration
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Create mysqldump command
        $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > {$path}";
        
        try {
            exec($command, $output, $return_var);
            
            if ($return_var === 0) {
                $this->info("Database backup created: {$filename}");
                
                // Log backup activity
                LogAktivitas::log(
                    null,
                    null,
                    'system_backup',
                    "Database backup created: {$filename}",
                    null,
                    ['backup_file' => $filename, 'size' => filesize($path)]
                );
            } else {
                $this->error("Database backup failed");
            }
        } catch (\Exception $e) {
            $this->error("Backup error: " . $e->getMessage());
        }
    }

    private function archiveReports()
    {
        $monthlyReports = [
            'applicants' => DB::table('applicants')->whereMonth('created_at', now()->subMonth()->month)->get(),
            'payments' => DB::table('payments')->whereMonth('created_at', now()->subMonth()->month)->get(),
            'logs' => DB::table('log_aktivitas')->whereMonth('created_at', now()->subMonth()->month)->get()
        ];

        $archiveData = json_encode($monthlyReports, JSON_PRETTY_PRINT);
        $filename = 'archive_' . now()->subMonth()->format('Y-m') . '.json';
        
        Storage::disk('local')->put('archives/' . $filename, $archiveData);
        
        $this->info("Monthly report archived: {$filename}");
    }

    private function cleanupOldBackups()
    {
        $backupPath = storage_path('app/backups/');
        
        if (is_dir($backupPath)) {
            $files = glob($backupPath . 'backup_ppdb_*.sql');
            
            // Keep only last 30 backups
            if (count($files) > 30) {
                usort($files, function($a, $b) {
                    return filemtime($a) - filemtime($b);
                });
                
                $filesToDelete = array_slice($files, 0, count($files) - 30);
                
                foreach ($filesToDelete as $file) {
                    unlink($file);
                }
                
                $this->info("Cleaned up " . count($filesToDelete) . " old backup files");
            }
        }
    }
}