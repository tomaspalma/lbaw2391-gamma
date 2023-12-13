<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\DB;


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
        return $this->group_owners()->paginate(10)->merge($this->users()->paginate(10));
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id');
    }


    public function getGroupImage()
    {
        return FileController::get('group', $this->id);
    }

    public function getBannerImage()
    {
        return FileController::get('group_banner', $this->id);
    }

    public function group_owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_owner', 'group_id', 'user_id');
    }
}
