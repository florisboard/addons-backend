<?php

namespace App\Http\Resources\Release;

use App\Http\Resources\User\UserResource;
use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Release */
class ReleaseFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'user_id' => $this->user_id,
            'version' => $this->version,
            'description' => $this->description,
            /* @var int */
            'downloads_count' => round($this->downloads_count),
            /* @var string */
            'created_at' => $this->created_at,
            /* @var string */
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->user),
        ];
    }
}
