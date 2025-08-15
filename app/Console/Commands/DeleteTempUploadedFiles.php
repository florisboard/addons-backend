<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DeleteTempUploadedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-temp-uploaded-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $startTime = microtime(true);
        foreach (Storage::files('tmp') as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::lastModified($file));

            $this->info("Checking $file");
            if (now()->diffInDays($lastModified) > 20) {
                Storage::delete($file);
                $this->info("Deleted $file");
            }
        }
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $this->info("[{$executionTime}] Deleting temp files finished successfully.");
    }
}
