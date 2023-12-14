<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PollOptionVote;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'poll_id'
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(PollOptionVote::class);
    }
}
