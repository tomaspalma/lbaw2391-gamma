<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GroupInvite;


class GroupInviteNot extends Model
{
    use HasFactory;

    protected $table = 'group_invitation_nots';
    public $timestamps = false;
    protected $fillable = [
        'group_invitation_id',
        'read'
    ];

    public function groupInvite(): BelongsTo
    {
        return $this->belongsTo(GroupInvite::class, 'group_invitation_id');
    }
    
}
