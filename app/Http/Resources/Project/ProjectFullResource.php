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
            /* @var string */
            'created_at' => $this->created_at,
            /* @var string */
            'updated_at' => $this->updated_at,
            'image' => new ImageResource($this->image),
            'screenshots' => ImageResource::collection($this->whenLoaded('screenshots')),
            'user' => new UserResource($this->user),
            'category' => new CategoryResource($this->category),
            'maintainers' => UserResource::collection($this->maintainers),
            /* @var int */
            'reviews_avg_score' => round($this->whenAggregated('reviews', 'score', 'avg') ?? 0),
            /* @var int */
            'releases_sum_downloads_count' => ($this->whenAggregated('releases', 'downloads_count', 'sum') ?? 0),
            /* @var ReleaseFullResource|null */
            'latest_release' => new ReleaseFullResource($this->latestRelease),
            'reviews' => ReviewResource::collection($this->reviews),
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
