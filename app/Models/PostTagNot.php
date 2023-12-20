<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTagNot extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'post_tag_not';

    protected $fillable = [
        'post_id'
    ];
}
