<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use Illuminate\Http\Request;

class ReleasePolicy
{
    private ?Project $project;

    public function __construct(Request $request)
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
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('update', [$this->project]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Release $release): bool
    {
        return true;
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
