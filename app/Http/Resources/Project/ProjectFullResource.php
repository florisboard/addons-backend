<?php

namespace App\Http\Resources\Project;

use App\Enums\ProjectTypeEnum;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Media\ImageResource;
use App\Http\Resources\Release\ReleaseFullResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\User\UserResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin Project */
class ProjectFullResource extends JsonResource
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
            /* @var int */
            'category_id' => $this->category_id,
            /* @var int */
            'user_id' => $this->user_id,
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'package_name' => $this->package_name,
            'short_description' => $this->short_description,
            /* @var ProjectTypeEnum */
            'type' => $this->type,
            'description' => $this->description,
            'home_page' => $this->home_page,
            'support_email' => $this->support_email,
            'support_site' => $this->support_site,
            'donate_site' => $this->donate_site,
            'is_recommended' => $this->is_recommended,
            'is_active' => $this->is_active,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'image' => new ImageResource($this->image),
            'screenshots' => ImageResource::collection($this->whenLoaded('screenshots')),
            'user' => new UserResource($this->user),
            'category' => new CategoryResource($this->category),
            'maintainers' => UserResource::collection($this->maintainers),
            'reviews_avg_score' => (int) round($this->whenAggregated('reviews', 'score', 'avg') ?? 0),
            'releases_sum_downloads_count' => (int) ($this->whenAggregated('releases', 'downloads_count', 'sum') ?? 0),
            /* @var ReleaseFullResource|null */
            'latest_release' => new ReleaseFullResource($this->latestRelease),
            'reviews' => ReviewResource::collection($this->reviews),
            'reviews_count' => (int) $this->whenCounted('reviews'),
            'one_reviews_count' => (int) $this->whenCounted('one_reviews'),
            'two_reviews_count' => (int) $this->whenCounted('two_reviews'),
            'three_reviews_count' => (int) $this->whenCounted('three_reviews'),
            'four_reviews_count' => (int) $this->whenCounted('four_reviews'),
            'five_reviews_count' => (int) $this->whenCounted('five_reviews'),
        ];
    }
}
