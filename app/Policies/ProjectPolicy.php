<?php

namespace App\Policies;

use App\Enums\StatusEnum;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function __construct(private readonly ProjectService $projectService) {}

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
    public function view(?User $user, Project $project): Response
    {
        return ($project->status === StatusEnum::Approved || $user?->id === $project->user_id)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): Response
    {
        if ($project->status === StatusEnum::Pending) {
            return Response::deny("You can't update the project if it's in Pending state.");
        }

        if ($project->latestChangeProposal?->status === StatusEnum::Pending) {
            return Response::deny("You can't update the project if there's a change proposal in Pending state.");
        }

        return $this->projectService->isMaintainer($user->id, $project)
            ? Response::allow()
            : Response::deny("You're not a maintainer of this project");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
