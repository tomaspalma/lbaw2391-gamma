<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReactionNot extends Model
{
    use HasFactory;

    protected $table = 'reaction_not';

    public function reaction(): BelongsTo {
        return $this->belongsTo(Reaction::class);
    }
}
