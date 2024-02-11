<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\AuthResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Rules\Username;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * @return AnonymousResourceCollection<LengthAwarePaginator<UserResource>>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.username' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
        ]);

        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::partial('username'),
            ])
            ->fastPaginate(20);

        return UserResource::collection($users);
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

        if ($request->input('email') !== Auth::user()->email) {
            $this->userService->ensureUserPasswordMatch(Auth::user(), $request->input('current_password'));
            Auth::user()->update([
                'email' => $request->input('email'),
                'email_verified_at' => null,
            ]);
        }

        if ($request->input('username') !== Auth::user()->username) {
            $this->userService->ensureUserCanUpdateUsername(Auth::user());
            Auth::user()->update([
                'username' => $request->input('username'),
                'username_changed_at' => now(),
            ]);
        }

        if ($request->filled('new_password')) {
            $this->userService->ensureUserPasswordMatch(Auth::user(), $request->input('current_password'));
            Auth::user()->update([
                'password' => Hash::make($request->input('new_password')),
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
