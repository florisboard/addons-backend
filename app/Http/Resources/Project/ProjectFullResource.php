<?php

namespace App\Http\Resources\Project;

use App\Enums\ProjectTypeEnum;
use App\Enums\StatusEnum;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ChangeProposalResource;
use App\Http\Resources\Media\ImageResource;
use App\Http\Resources\Release\ReleaseFullResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\User\UserResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project
 * @property int $one_reviews_count
 * @property int $two_reviews_count
 * @property int $three_reviews_count
 * @property int $four_reviews_count
 * @property int $five_reviews_count
 * @property string $reviews_avg_score
 * @property string $releases_sum_downloads_count
 */
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
            /** @var int */
            'id' => $this->id,
            /** @var int */
            'category_id' => $this->category_id,
            /** @var int */
            'user_id' => $this->user_id,
            'title' => $this->title,
            'package_name' => $this->package_name,
            'short_description' => $this->short_description,
            /** @var ProjectTypeEnum */
            'type' => $this->type,
            'description' => $this->description,
            'links' => [
                'source_code' => data_get($this->links, 'source_code'),
            ],
            'is_recommended' => $this->is_recommended,
            /** @var StatusEnum */
            'status' => $this->status,
            /** @var string */
            'created_at' => $this->created_at,
            /** @var string */
            'updated_at' => $this->updated_at,
            /** @var ImageResource|null */
            'image' => new ImageResource($this->image),
            'screenshots' => ImageResource::collection($this->screenshots),
            'user' => new UserResource($this->user),
            'category' => new CategoryResource($this->category),
            'maintainers' => UserResource::collection($this->maintainers),
            /** @var int */
            'reviews_avg_score' => round((int) $this->reviews_avg_score),
            /** @var int */
            'releases_sum_downloads_count' => (int) $this->releases_sum_downloads_count,
            /** @var ReleaseFullResource|null */
            'latest_release' => new ReleaseFullResource($this->latestRelease),
            'reviews' => ReviewResource::collection($this->reviews),
            'user_review' => new ReviewResource($this->whenLoaded('userReview')),
            /** @var int */
            'reviews_count' => $this->reviews_count,
            /** @var int */
            'one_reviews_count' => $this->one_reviews_count,
            /** @var int */
            'two_reviews_count' => $this->two_reviews_count,
            /** @var int */
            'three_reviews_count' => $this->three_reviews_count,
            /** @var int */
            'four_reviews_count' => $this->four_reviews_count,
            /** @var int */
            'five_reviews_count' => $this->five_reviews_count,
            'latest_change_proposal' => new ChangeProposalResource($this->whenLoaded('latestChangeProposal')),
        ];
    }
}
