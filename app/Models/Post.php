<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;
use App\Models\Reaction;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    protected $fillable = [
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
        return $this->hasMany(Reaction::class);
    }
}
