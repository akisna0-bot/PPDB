<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Update status otomatis setiap 30 menit
        $schedule->command('ppdb:auto-update')
                 ->everyThirtyMinutes()
                 ->withoutOverlapping();

        // Kirim notifikasi setiap jam
        $schedule->command('ppdb:send-notifications')
                 ->hourly()
                 ->withoutOverlapping();

        // Backup harian pada jam 2 pagi
        $schedule->command('ppdb:backup')
                 ->dailyAt('02:00')
                 ->withoutOverlapping();

        // Cleanup log lama setiap minggu
        $schedule->call(function () {
            \App\Models\LogAktivitas::where('created_at', '<', now()->subMonths(3))->delete();
        })->weekly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}