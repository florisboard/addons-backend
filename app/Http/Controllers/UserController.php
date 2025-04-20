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
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function __construct() {}

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
            ->paginate(20);

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

    public function update(Request $request): AuthResource
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:33', new Username],
        ]);

        //        if ($request->input('username') !== Auth::user()->username) {
        //            $this->userService->ensureUserCanUpdateUsername(Auth::user());
        //            Auth::user()->update([
        //                'username' => $request->input('username'),
        //                'username_changed_at' => now(),
        //            ]);
        //        }

        return new AuthResource(Auth::user());
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            /** @var string */
            'username' => ['required', 'string', 'min:3', 'max:255', Rule::in(Auth::user()?->username)],
        ]);

        Auth::guard('web')->logout();
        Auth::user()->delete();

        return new JsonResponse(['message' => "You've deleted your account successfully."]);
    }
}
