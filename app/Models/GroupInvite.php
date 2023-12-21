<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\DB;


use App\Models\GroupRequest;

class GroupInvite extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'owner_id',
        'user_id',
        'group_id',
        'is_accepted'
    ];

    protected $table = 'group_invitations';
}
