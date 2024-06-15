<?php

namespace App\Console\Commands;

use App\Models\Domain;
use Illuminate\Console\Command;

class DeleteUnverifiedDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-domains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unverified domains.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Domain::query()
            ->whereNull('verified_at')
            ->where('created_at', '<', now()->subDay())
            ->delete();
    }
}
