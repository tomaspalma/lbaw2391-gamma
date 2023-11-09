<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;

class PostResource extends JsonResource
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
            'author' => $user,
            'title' => $this->title,
            'content' => $this->content,
            'is_private' => $this->is_private,
            'attachment' => $this->attachment,
            'date' => $this->date
        ];
    }
}
