<?php

namespace App\Console;

use App\Console\Commands\DeleteTempUploadedFiles;
use App\Console\Commands\DeleteUnverifiedDomains;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(DeleteTempUploadedFiles::class)->hourly();
        $schedule->command('livewire:configure-s3-upload-cleanup')->hourly();
        $schedule->command(DeleteUnverifiedDomains::class)->hourly();
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
