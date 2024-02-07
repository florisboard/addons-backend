<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\Media\ImageResource;
use App\Http\Resources\Release\ReleaseResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'slug' => Str::slug($this->name),
            'package_name' => $this->package_name,
            'short_description' => $this->short_description,
            'type' => $this->type,
            'is_recommended' => $this->is_recommended,
            'is_active' => $this->is_active,
            /* @var string */
            'created_at' => $this->created_at,
            /* @var string */
            'updated_at' => $this->updated_at,
            'image' => new ImageResource($this->image),
            /* @var int */
            'reviews_avg_score' => round($this->whenAggregated('reviews', 'score', 'avg', null, 0)),
            /* @var int */
            'releases_sum_downloads_count' => $this->whenAggregated('releases', 'downloads_count', 'sum', null, 0),
            /* @var ReleaseResource|null */
            'latest_release' => new ReleaseResource($this->latestRelease),
            /* @var int */
            'reviews_count' => $this->reviews_count,
        ];
    }
}
