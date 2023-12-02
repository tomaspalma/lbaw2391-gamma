<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;
    protected $table = 'friend_request';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'friend_id',
    ];
}
