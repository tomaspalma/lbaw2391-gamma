<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'seen',
        'is_acceptance'
    ];

    public function groupRequest(): HasOne
    {
        return $this->hasOne(GroupRequest::class, 'id', 'group_request_id');
    }
}
