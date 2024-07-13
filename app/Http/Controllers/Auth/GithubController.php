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
    private array $scopes = [];

    public function redirect(): JsonResponse
    {
        $url = Socialite::with('github')
            ->setScopes($this->scopes)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return new JsonResponse(['url' => $url]);
    }

    public function callback(): RedirectResponse
    {
        $result = Socialite::with('github')
            ->setScopes($this->scopes)
            ->stateless()
            ->user();

        $user = User::firstOrCreate([
            'provider_id' => $result->getId(),
            'provider' => AuthProviderEnum::Github,
        ], [
            'username' => $result->getNickname(),
            'password' => Str::password(12),
        ]);

        Auth::login($user, true);

        return response()->redirectToIntended(sprintf('%s/users/%s', config('app.frontend_url'), Auth::user()->username));
    }
}
