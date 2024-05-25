<?php

namespace App\Observers;

use App\Models\User;
use App\Services\DomainService;
use Illuminate\Support\Str;
use Random\RandomException;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @throws RandomException
     */
    public function created(User $user): void
    {
        $user->domains()->firstOrCreate([
            'name' => sprintf('%s.github.io', Str::lower($user->username)),
            'verified_at' => now(),
            'verification_code' => DomainService::generateVerificationCode(),
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
