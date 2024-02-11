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
        foreach (Storage::directories('tmp') as $directory) {
            $directoryLastModified = Carbon::createFromTimestamp(Storage::lastModified($directory));

            if (now()->diffInHours($directoryLastModified) > 24) {
                Storage::deleteDirectory($directory);
            }
        }
    }
}
