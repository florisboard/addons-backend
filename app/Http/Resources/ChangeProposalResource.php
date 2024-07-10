<?php

namespace App\Http\Resources;

use App\Enums\StatusEnum;
use App\Models\ChangeProposal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ChangeProposal */
class ChangeProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // We only show the reviewerDescription when the change proposal is rejected
        $reviewerDescription = $this->status === StatusEnum::Rejected
            ? $this->reviewer_description
            : null;

        return [
            'id' => $this->id,
            /** @var StatusEnum */
            'status' => $this->status,
            /** @var string|null */
            'reviewer_description' => $reviewerDescription,
            'data' => $this->data,
            /** @var string */
            'updated_at' => $this->updated_at,
            /** @var string */
            'created_at' => $this->created_at,
        ];
    }
}
