<?php

namespace App\Policies;

use App\Enums\StatusEnum;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ProjectPolicy
{
    public function __construct(private readonly ProjectService $projectService)
    {
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

    public function publish(User $user, Project $project): Response
    {
        if ($project->status !== StatusEnum::Draft) {
            return Response::deny("You can publish a project to get reviewed only when It's in Draft");
        }

        if (!$this->projectService->isMaintainer($user->id, $project)) {
            return Response::deny('You are not the maintainer of this project');
        }

        if ($project->releases()->count() <= 0) {
            return Response::deny('The project must have at least one release to get published and reviewed.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): Response
    {
        if ($project->status === StatusEnum::UnderReview) {
            return Response::deny("You can't update the project if it's in Pending state.");
        }

        if ($project->latestChangeProposal?->status === StatusEnum::UnderReview) {
            return Response::deny("You can't update the project if there's a change proposal in Pending state.");
        }

        return $this->projectService->isMaintainer($user->id, $project)
            ? Response::allow()
            : Response::deny("You're not a maintainer of this project");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): Response
    {
        if ($user->id !== $project->user_id) {
            return Response::deny('You are not the owner of this project');
        }

        if ($project->status !== StatusEnum::Draft) {
            return Response::deny("You can only delete a project if it's in Draft mode");
        }

        return Response::allow();
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
