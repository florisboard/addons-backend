<?php

namespace App\Http\Resources;

use App\Http\Resources\Media\ImageResource;
use App\Http\Resources\User\UserResource;
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
            'type' => $this->type,
            'description' => $this->description,
            'home_page' => $this->home_page,
            'support_email' => $this->support_email,
            'support_site' => $this->support_site,
            'donate_site' => $this->donate_site,
            'is_recommended' => $this->is_recommended,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'image' => new ImageResource($this->whenLoaded('image')),
            'screenshots' => ImageResource::collection($this->whenLoaded('screenshots')),
            'user' => new UserResource($this->whenLoaded('user')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'maintainers' => UserResource::collection($this->whenLoaded('maintainers')),
            'reviews_avg_score' => $this->whenAggregated('reviews', 'score', 'avg'),
            'releases_sum_downloads_count' => intval($this->whenAggregated('releases', 'downloads_count', 'sum') ?? 0),
            'latest_release' => new ReleaseResource($this->whenLoaded('latestRelease')),
            'releases' => ReleaseResource::collection($this->whenLoaded('releases')),
            /* @var int */
            'reviews_count' => $this->whenCounted('reviews'),
            /* @var int */
            'one_reviews_count' => $this->whenCounted('one_reviews'),
            /* @var int */
            'two_reviews_count' => $this->whenCounted('two_reviews'),
            /* @var int */
            'three_reviews_count' => $this->whenCounted('three_reviews'),
            /* @var int */
            'four_reviews_count' => $this->whenCounted('four_reviews'),
            /* @var int */
            'five_reviews_count' => $this->whenCounted('five_reviews'),
        ];
    }
}
