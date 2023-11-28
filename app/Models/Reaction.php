<?php

namespace App\Models;

use App\Enums\ReactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    use HasFactory;
    protected $table = 'reaction';

    public $timestamps = false;

    protected $fillable = [
        'author',
        'post_id',
        'comment_id',
        'type',
    ];

    protected $casts = [
        'type' => ReactionType::class,
    ];

    public function author() : BelongsTo 
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function post() : BelongsTo 
    {
        return $this->belongsTo(Post::class, "post_id");
    }

    public function comment() : BelongsTo 
    {
        return $this->belongsTo(Comment::class, "comment_id");
    }
}
