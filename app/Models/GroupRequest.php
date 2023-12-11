<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Controllers\FileController;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class GroupRequest extends Model
{
    protected $table = 'group_request';

    protected $fillable = [
        'id',
        'user_id',
        'group_id',
        'is_accepted',
        'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
?>