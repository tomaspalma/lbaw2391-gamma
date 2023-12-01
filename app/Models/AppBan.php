<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AppBan extends Model
{
    use HasFactory;
    protected $table = 'app_ban';

    public $timestamps = false;

    protected $fillable = [
        'reason',
        'admin_id',
        'banned_user_id'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'banned_user_id');
    }

    public function appeal_model(): BelongsTo {
        return $this->belongsTo(AppBanAppeal::class, 'appeal');
    }
}
