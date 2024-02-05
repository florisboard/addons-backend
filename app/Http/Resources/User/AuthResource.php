<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /* @var int */
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            /* @var string|null */
            'email_verified_at' => $this->email_verified_at,
            /* @var string|null */
            'username_changed_at' => $this->username_changed_at,
            /* @var string */
            'created_at' => $this->created_at,
            /* @var string */
            'updated_at' => $this->updated_at,
        ];
    }
}
