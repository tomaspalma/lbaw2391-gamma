<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupBan extends Model
{
    use HasFactory;

    protected $table = 'group_ban';

    public $timestamps = false;

    protected $fillable = [
        'reason',
        'group_owner_id',
        'banned_user_id',
        'group_id',
    ];
}
