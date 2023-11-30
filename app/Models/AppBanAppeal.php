<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AppBanAppeal extends Model
{
    use HasFactory;

    protected $table = 'appeal';

    protected $fillable = [
        'reason'
    ];

    public function appban(): HasOne {
        return $this->hasOne(AppBan::class, "appeal");
    }
}
