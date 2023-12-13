<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequestNot extends Model
{
    use HasFactory;

    protected $table = 'friend_request_not';
    public $timestamps = false;


    protected $fillable = [
        'user_id',
        'friend_id',
        'seen'
    ];

    public function friendRequest(): BelongsTo
    {
        return $this->belongsTo(FriendRequest::class, 'friend_request');
    }

    public function sender()
    {
        return $this->friendRequest->sender();
    }

    public function receiver()
    {
        return $this->friendRequest->receiver();
    }

    public function verb(): string
    {
        if ($this->is_accepted === null) {
            return "sent you a friend request";
        } else if ($this->is_accepted === true) {
            return "accepted your friend request";
        } else {
            return "declined your friend request";
        }
    }
}
