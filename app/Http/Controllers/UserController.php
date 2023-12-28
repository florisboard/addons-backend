<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\AuthResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Rules\Username;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function me(): AuthResource
    {
        return new AuthResource(Auth::user());
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): AuthResource
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:30', new Username],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore(Auth::id())],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'new_password' => ['nullable', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->email !== Auth::user()->email) {
            $this->userService->ensureUserPasswordMatch(Auth::user(), $request->current_password);
            Auth::user()->update([
                'email' => $request->email,
                'email_verified_at' => null,
            ]);
        }

        if ($request->username !== Auth::user()->username) {
            $this->userService->ensureUserCanUpdateUsername(Auth::user());
            Auth::user()->update([
                'username' => $request->username,
                'username_changed_at' => now(),
            ]);
        }

        if ($request->has('new_password')) {
            $this->userService->ensureUserPasswordMatch(Auth::user(), $request->current_password);
            Auth::user()->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        return new AuthResource(Auth::user());
    }

    /**
     * @throws ValidationException
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('auth.failed'),
            ]);
        }

        Auth::guard('web')->logout();
        Auth::user()->delete();

        return new JsonResponse(['message' => "You've deleted your account successfully."]);
    }
}
