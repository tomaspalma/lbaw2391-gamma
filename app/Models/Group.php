<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'is_private',
        'tsvectors'
    ];


    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, "group_id");
    }

    public function all_users()
    {
        return $this->group_owners->concat($this->users);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id');
    }

    public function group_owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_owner', 'group_id', 'user_id');
    }
}
