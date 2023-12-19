<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GroupRequest;


class GroupRequestNot extends Model
{
    use HasFactory;

    protected $table = 'group_request_not';
    public $timestamps = false;


    protected $fillable = [
        'group_request_id',
        'seen'
    ];

    public function groupRequest(): BelongsTo
    {
        return $this->belongsTo(GroupRequest::class, 'friend_request_id');
    }

    /*
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
    */
}
