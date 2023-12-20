<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Http\Controllers\FileController;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class GroupRequest extends Model
{
    protected $table = 'group_request';
    protected $primaryKey = 'id';


    public $timestamps = false;

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

    public function approve(){
        $id = $this->id;

        DB::transaction(function () use ($id) {
            DB::table('group_request')
                ->where('id', $id)
                ->update(['is_accepted' => true]);
        });
    }

    public function decline(){
        $id = $this->id;

        DB::transaction(function () use ($id) {
            DB::table('group_request')
                ->where('id', $id)
                ->delete();
        });
    }

    public function remove(){
        $this->decline();
    }

}
?>