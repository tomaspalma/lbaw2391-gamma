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
}
