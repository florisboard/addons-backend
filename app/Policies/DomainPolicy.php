<?php

namespace App\Policies;

use App\Models\Domain;
use App\Models\User;
use App\Services\DomainService;
use Illuminate\Auth\Access\Response;

class DomainPolicy
{
    public function __construct(private readonly DomainService $domainService)
    {
    }

    public function isTheOwner(User $user, Domain $domain): bool
    {
        return $user->id === $domain->user_id && !$this->domainService->isInExcludedDomains($domain->name);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Domain $domain): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->domains()->whereNull('verified_at')->exists()
            ? Response::deny('An unverified domain exists within your account. Please verify this domain or remove it to proceed with creating a new one.')
            : Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Domain $domain): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Domain $domain): bool
    {
        return $this->isTheOwner($user, $domain);
    }

    public function verify(User $user, Domain $domain): bool
    {
        return $this->isTheOwner($user, $domain);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Domain $domain): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Domain $domain): bool
    {
        return false;
    }
}
