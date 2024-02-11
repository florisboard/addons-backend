<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * @throws ValidationException
     */
    public function ensureUserPasswordMatch(User $user, ?string $password, string $passwordField = 'current_password'): void
    {
        if (! $password || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                $passwordField => __('auth.failed'),
            ]);
        }
    }

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
