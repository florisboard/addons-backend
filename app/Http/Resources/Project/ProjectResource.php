<?php

namespace App\Http\Resources\Project;

use App\Enums\StatusEnum;
use App\Http\Resources\Media\ImageResource;
use App\Http\Resources\Release\ReleaseResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project
 * @property string $reviews_avg_score
 * @property string $releases_sum_downloads_count
 */
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
            'title' => $this->title,
            'package_name' => $this->package_name,
            'short_description' => $this->short_description,
            'type' => $this->type,
            'is_recommended' => $this->is_recommended,
            /** @var StatusEnum */
            'status' => $this->status,
            /** @var string */
            'created_at' => $this->created_at,
            /** @var string */
            'updated_at' => $this->updated_at,
            /** @var ImageResource|null */
            'image' => new ImageResource($this->image),
            /** @var int */
            'reviews_avg_score' => round((int) $this->reviews_avg_score),
            /** @var int */
            'releases_sum_downloads_count' => (int) $this->releases_sum_downloads_count,
            /** @var ReleaseResource|null */
            'latest_release' => new ReleaseResource($this->latestRelease),
            /** @var int */
            'reviews_count' => $this->reviews_count,
        ];
    }
}
