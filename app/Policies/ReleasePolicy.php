<?php

namespace App\Policies;

use App\Enums\StatusEnum;
use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class ReleasePolicy
{
    private ?Project $project;

    public function __construct(Request $request, private readonly ProjectService $projectService)
    {
        // @phpstan-ignore-next-line
        $this->project = $request->route('project');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Release $release): bool
    {
        return $release->status === StatusEnum::Approved;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if (! $this->projectService->isMaintainer($user->id, $this->project)) {
            return Response::deny("You're not a maintainer of this project");
        }

        if ($this->project->releases()->where('status', StatusEnum::UnderReview)->exists()) {
            return Response::deny('You already have a pending release.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Release $release): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Release $release): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Release $release): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Release $release): bool
    {
        return false;
    }
}
