<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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


    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
