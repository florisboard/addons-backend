<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\AuthResource;
use App\Models\User;
use App\Rules\Username;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        /** @var array<string,mixed> $validated */
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:30', new Username],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([...$validated, 'password' => Hash::make($request->password)]);

        event(new Registered($user));

        Auth::login($user);

        return new JsonResponse(new AuthResource($user), 201);
    }
}
