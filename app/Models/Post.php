<?php

namespace App\Models;

use App\Enums\ReactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;
use App\Models\Reaction;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'author',
        'title',
        'content',
        'attachment',
        'group_id',
        'is_private'
    ];

    protected $hidden = [
        'tsvectors',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "author");
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class, "post_id");
    }

    public function reactionsMap(): array 
    {
        $reactions = [];
        foreach ($this->reactions as $reaction) {
            $icon = $reaction->type->getViewIcon();
            $color = $reaction->type->getViewColor();
            if (!isset($reactions[$icon])) {
                $reactions[$icon] = [1, $color];
            } else {
                $reactions[$icon][0] += 1;
            }
        }
        
        return $reactions;
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, "post_id");
    }

    public function format_date(): string
    {
        $dateTime = new \DateTime($this->date);
        $now = new \DateTime();
        $interval = $dateTime->diff($now);
        if ($interval->y > 0) {
            if ($interval->y == 1)
                return $interval->y . " year ago";
            else
                return $interval->y . " years ago";
        } else if ($interval->m > 0) {
            if ($interval->m == 1)
                return $interval->m . " month ago";
            else
                return $interval->m . " months ago";
        } else if ($interval->d > 0) {
            if ($interval->d == 1)
                return $interval->d . " day ago";
            else
                return $interval->d . " days ago";
        } else if ($interval->h > 0) {
            if ($interval->h == 1)
                return $interval->h . " hour ago";
            else
                return $interval->h . " hours ago";
        } else if ($interval->i > 0) {
            if ($interval->i == 1)
                return $interval->i . " minute ago";
            else
                return $interval->i . " minutes ago";
        } else {
            return "Just now";
        }
    }
}
