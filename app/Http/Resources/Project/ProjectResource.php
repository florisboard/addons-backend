<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\Media\ImageResource;
use App\Http\Resources\Release\ReleaseResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class ProjectResource extends JsonResource
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
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'package_name' => $this->package_name,
            'short_description' => $this->short_description,
            'type' => $this->type,
            'is_recommended' => $this->is_recommended,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'image' => new ImageResource($this->whenLoaded('image')),
            'reviews_avg_score' => round($this->whenAggregated('reviews', 'score', 'avg') ?? 0),
            'releases_sum_downloads_count' => intval($this->whenAggregated('releases', 'downloads_count', 'sum') ?? 0),
            'latest_release' => new ReleaseResource($this->whenLoaded('latestRelease')),
            /* @var int */
            'reviews_count' => $this->whenCounted('reviews'),
        ];
    }
}
