<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;
use App\Models\Post;
use App\Models\Reaction;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'post_id',
        'author',
        'content'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "author");
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, "post_id");
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class, "comment_id");
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
