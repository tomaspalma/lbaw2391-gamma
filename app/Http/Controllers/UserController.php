<?php

namespace App\Http\Controllers;

use App\Models\Comment as ModelsComment;
use App\Models\GroupOwner;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/*
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ ONLY;

UPDATE post
SET author = 0
WHERE author = OLD.id;

UPDATE comment
SET author = 0
WHERE user_id = OLD.id;

UPDATE reaction
SET author = 0
WHERE user_id = OLD.id;

DELETE
FROM group_owner
WHERE user_id = OLD.id;

DELETE
FROM post_tag_not ptn
JOIN post p ON(ptn.post_id = p.id)
JOIN users u ON (p.author = u.id)
WHERE u.id = OLD.id;

DELETE
FROM group_request_not grn
JOIN group_request gr ON(grn.group_request_id = gr.id)
JOIN users u ON (g.user_id = u.id)
WHERE u.id = OLD.id;

DELETE
FROM friend_request_not frn
JOIN friend_request fr ON(frn.friend_request = fr.id)
JOIN users u ON (fr.user_id = u.id OR fr.friend_id = u.id)
WHERE u.id = OLD.id;

DELETE
FROM comment_not cn
JOIN comment c ON(cn.comment_id = c.id)
JOIN users u ON (c.author = u.id)
WHERE u.id = OLD.id;

DELETE
FROM reaction_not rn
JOIN reaction r ON(rn.reaction_id = r.id)
JOIN users u ONjohndoe@example.com (r.author = u.id)
WHERE u.id = OLD.id;

DELETE
FROM group_user
WHERE user_id = OLD.id;

END TRANSACTION;

*/

class UserController extends Controller
{
    /**
     * This should be used inside a transaction
     * */
    private function switch_user_content_to_deleted_user($user_id)
    {
        echo $user_id;

        DB::table('post')->where('author', $user_id)->update(['author' => 0]);
        DB::table('comment')->where('author', $user_id)->update(['author' => 0]);
        DB::table('reaction')->where('author', $user_id)->update(['author' => 0]);

        DB::table('group_owner')->where('user_id', $user_id)->delete();
        DB::table('group_user')->where('user_id', $user_id)->delete();

        DB::table('post_tag_not as ptn')
            ->join('post as p', 'ptn.post_id', '=', 'p.id')
            ->join('users as u', 'p.author', '=', 'u.id')
            ->where('u.id', '=', $user_id)
            ->delete();

        DB::table('group_request_not as grn')
            ->join('group_request as gr', 'grn.group_request_id', '=', 'gr.id')
            ->join('users as u', 'gr.user_id', '=', 'u.id')
            ->where('u.id', '=', $user_id)
            ->delete();

        DB::table('friend_request_not as frn')
            ->join('friend_request as fr', 'frn.friend_request', '=', 'fr.id')
            ->join('users as u', function ($join) {
                $join->on('u.id', '=', 'fr.user_id')
                    ->orOn('u.id', '=', 'fr.friend_id');
            })
            ->where('u.id', '=', $user_id)
            ->delete();

        DB::table('friend_request')
            ->where('user_id', '=', $user_id)
            ->orWhere('friend_id', '=', $user_id)
            ->delete();

        DB::table('comment_not as cn')
            ->join('comment as c', 'cn.comment_id', '=', 'c.id')
            ->join('users as u', 'c.author', '=', 'u.id')
            ->where('u.id', '=', $user_id)
            ->delete();

        DB::table('reaction_not as rn')
            ->join('reaction as r', 'rn.reaction_id', '=', 'r.id')
            ->join('users as u', 'u.id', '=', 'r.author')
            ->where('u.id', $user_id)
            ->delete();

        DB::table('friends')
            ->where('friend1', '=', $user_id)
            ->orWhere('friend2', '=', $user_id)
            ->delete();

        DB::table('users')
            ->where('id', '=', $user_id)
            ->delete();
    }

    public function delete_user(String $username)
    {
        $user = User::where('username', $username)->get();
        $user_id = $user[0]->id;

        if ($user === null) {
            abort(404);
        }

        DB::transaction(function () use ($user_id) {
            $this->switch_user_content_to_deleted_user($user_id);
        });
    }
}
