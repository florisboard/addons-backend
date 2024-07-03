<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAsAdmin extends Controller
{
    public function __invoke(Request $request): string
    {
        abort_unless(app()->isLocal(), 404);

        $user = User::firstOrFail();
        $user->update(['is_admin' => true]);
        Auth::loginUsingId($user->id);

        return "You're logged in as an admin.";
    }
}
