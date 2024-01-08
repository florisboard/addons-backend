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
            'is_active' => $this->is_active,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'image' => new ImageResource($this->whenLoaded('image')),
            'reviews_avg_score' => (int) round($this->whenAggregated('reviews', 'score', 'avg') ?? 0),
            'releases_sum_downloads_count' => (int) ($this->whenAggregated('releases', 'downloads_count', 'sum') ?? 0),
            'latest_release' => new ReleaseResource($this->latestRelease),
            'reviews_count' => (int) $this->reviews_count,
        ];
    }
}
