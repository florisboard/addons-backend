<?php

namespace App\Http\Resources;

use App\Models\Domain;
use App\Services\DomainService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Domain */
class DomainResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $domainService = app(DomainService::class);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'verification_text' => $domainService->generateVerificationText($this->verification_code),
            'verified_at' => $this->verified_at,
            /** @var bool */
            'is_reserved' => $domainService->isInExcludedDomains($this->name),
        ];
    }
}
