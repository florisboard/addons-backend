<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirect(): JsonResponse
    {
        $url = Socialite::with('github')->stateless()->redirect()->getTargetUrl();

        return new JsonResponse(['url' => $url]);
    }

    public function callback(): RedirectResponse
    {
        $result = Socialite::with('github')->stateless()->user();
        $usernameExists = User::where('username', $result->nickname)->exists();

        $user = User::firstOrCreate([
            'email' => $result->email,
        ], [
            'username' => $usernameExists ? $result->nickname.rand(1000, 9999) : $result->nickname,
            'password' => Str::password(),
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return response()->redirectToIntended(env('FRONTEND_URL').'?'.http_build_query(['loginSuccessful' => false]));
    }
}
