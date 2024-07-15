<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginAsController extends Controller
{
    public function admin(): string
    {
        abort_unless(app()->isLocal(), 404);

        $user = User::firstOrFail();
        $user->update(['is_admin' => true]);
        Auth::loginUsingId($user->id);

        return "You're logged in as an admin.";
    }

    public function user(int $id): string
    {
        abort_unless(app()->isLocal(), 404);

        User::findOrFail($id);
        Auth::loginUsingId($id);

        return "You're logged in as $id.";
    }
}
