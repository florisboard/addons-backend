<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AuthProviderEnum;
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

        $user = User::firstOrCreate([
            'provider_id' => $result->getId(),
            'provider' => AuthProviderEnum::Github,
        ], [
            'username' => $result->getNickname(),
            'password' => Str::password(12),
        ]);

        Auth::login($user, true);

        return response()->redirectToIntended(config('app.frontend_url').'?'.http_build_query(['authSuccessful' => true]));
    }
}
