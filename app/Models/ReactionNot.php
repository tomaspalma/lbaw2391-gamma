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

    public $timestamps = false;
    
    /*
      * Translates the type of the reaction to the corresponding verb
      */
    public function verb(): string {
         return $this->reaction->type->getVerb();
    }

    public function reaction(): BelongsTo {
        return $this->belongsTo(Reaction::class);
    }
}
