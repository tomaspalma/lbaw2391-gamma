<?php

namespace App\Http\Controllers;

use App\Models\Comment as ModelsComment;
use App\Models\GroupOwner;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Http\Request;

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
    public function delete_user(String $username)
    {
        $user = User::where('username', $username)->get();
        $user_id = $user[0]->id;

        Post::where('author', $user_id)->update(['author' => 0]);
        ModelsComment::where('author', $user_id)->update(['author' => 0]);
        Reaction::where('author', $user_id)->update(['author' => 0]);
        GroupOwner::where('user_id', $user_id)->delete();

        if ($user === null) {
            abort(404);
        }

        $user[0]->delete();
    }
}
