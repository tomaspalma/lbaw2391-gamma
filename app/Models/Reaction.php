<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    use HasFactory;
    protected $table = 'reaction';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'author',
        'post_id',
        'comment_id',
        'type',
    ];

    public function post() : BelongsTo {
        return $this->belongsTo(Post::class, "post_id");
    }

    public function comment() : BelongsTo {
        return $this->belongsTo(Comment::class, "comment_id");
    }
}
