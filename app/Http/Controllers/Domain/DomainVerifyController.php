<?php

namespace App\Http\Controllers\Domain;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Services\DomainService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DomainVerifyController extends Controller
{
    public function __construct(private readonly DomainService $domainService) {}

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function store(Domain $domain): JsonResponse
    {
        $this->authorize('verify', $domain);

        if (! $this->domainService->hasVerificationText($domain->name, $domain->verification_code)) {
            throw ValidationException::withMessages(['message' => "Couldn't verify the domain please try again later."]);
        }

        $domain->update(['verified_at' => now()]);

        return new JsonResponse(['message' => 'Domain verified successfully.']);
    }
}
