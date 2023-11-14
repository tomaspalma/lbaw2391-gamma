<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
