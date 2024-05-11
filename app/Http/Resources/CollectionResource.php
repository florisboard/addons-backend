<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Collection */
class CollectionResource extends JsonResource
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
            'user_id' => $this->user_id,
            'title' => $this->title,
            'is_public' => $this->is_public,
            /* @var string */
            'created_at' => $this->created_at,
            /* @var string */
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            /* @var int */
            'projects_count' => $this->whenCounted('projects'),
        ];
    }
}
