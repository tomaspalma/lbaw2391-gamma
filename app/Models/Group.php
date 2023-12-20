<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\DB;


use App\Models\GroupRequest;

class Group extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'is_private',
        'tsvectors',
        'image'
    ];


    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, "group_id");
    }


    public function requests(): HasMany
    {
        return $this->hasMany(GroupRequest::class, 'group_id')->where('is_accepted', false);
    }

    public function all_users()
    {
        return $this->group_owners()->paginate(10)->merge($this->users()->paginate(10));
    }

    public function remove_request($user_id)
    {

        $group_id = $this->id;

        DB::transaction(function () use ($user_id, $group_id) {
            DB::table('group_request')
                ->where('group_id', $group_id)
                ->where('user_id', $user_id)
                ->delete();
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id');
    }

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_owner', 'group_id', 'user_id');
    }

    public function getGroupImage(string $size = 'original')
    {
        return FileController::get('group', $this->id, $size);
    }

    public function getBannerImage(string $size = 'original')
    {
        return FileController::get('group_banner', $this->id, $size);
    }

    public function group_owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_owner', 'group_id', 'user_id');
    }
}
