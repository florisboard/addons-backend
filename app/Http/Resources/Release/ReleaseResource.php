<?php

namespace App\Http\Resources\Release;

use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Release */
class ReleaseResource extends JsonResource
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
            'version_name' => $this->version_name,
            /** @var int */
            'version_code' => $this->version_code,
            /** @var string */
            'created_at' => $this->created_at,
            /** @var string */
            'updated_at' => $this->updated_at,
        ];
    }
}
