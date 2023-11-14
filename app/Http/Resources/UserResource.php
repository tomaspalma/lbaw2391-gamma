<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'image' => $this->image,
            'academic_status' => $this->academic_status,
            'display_name' => $this->display_name,
            'is_private' => $this->is_private,
            'role' => $this->role,
            'is_app_banned' => $this->is_app_banned()
        ];
    }
}
