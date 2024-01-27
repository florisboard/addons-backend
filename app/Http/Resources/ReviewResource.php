<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Review */
class ReviewResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            /* @var int */
            'score' => $this->score,
            'is_anonymous' => $this->is_anonymous,
            /* @var string */
            'created_at' => $this->created_at,
            /* @var string */
            'updated_at' => $this->updated_at,
            /* @var UserResource|null */
            'user' => $this->is_anonymous ? null : new UserResource($this->user),
        ];
    }
}
