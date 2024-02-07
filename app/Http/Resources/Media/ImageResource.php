<?php

namespace App\Http\Resources\Media;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Media */
class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        ray($this->getSrcset());

        return [
            'id' => $this->id,
            'placeholder' => $this->responsiveImages('media_library_original')->getPlaceholderSvg(),
            'url' => $this->getFullUrl(),
        ];
    }
}
