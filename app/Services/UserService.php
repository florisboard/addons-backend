<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * @throws ValidationException
     */
    public function ensureUserCanUpdateUsername(User $user): void
    {
        $requiredPassedDays = Carbon::now()->subDays(14);

        if ($requiredPassedDays->lte($user->username_changed_at)) {
            throw ValidationException::withMessages([
                'username' => 'You cannot change your username for 14 days.',
            ]);
        }
    }
}
