<?php

namespace App\Http\Resources;

use App\Http\Resources\Project\ProjectSlimResource;
use App\Http\Resources\Release\ReleaseFullResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Project */
class CheckUpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'project' => new ProjectSlimResource($this),
            /** @var ReleaseFullResource|null */
            'latest_release' => new ReleaseFullResource($this->latestRelease),
        ];
    }
}
