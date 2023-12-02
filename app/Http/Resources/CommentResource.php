<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::findOrFail($this->author);

        return [
            'id' => $this->id,
            'display_name' => $user->display_name,
            'username' => $user->username,
            'image' => $user->getProfileImage(),
            'content' => $this->content,
            'date' => $this->date
        ];
    }
}
