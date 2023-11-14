<?php

namespace App\Http\Controllers;

use App\Models\AppBan;
use App\Models\Comment as ModelsComment;
use App\Models\GroupOwner;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function unblock_user(String $username)
    {
        $user = User::where('username', '=', $username)->get()[0];

        if ($user->is_app_banned()) {
            AppBan::where('banned_user_id', $user->id)->delete();
        }
    }

    public function block_user(Request $request, String $username)
    {
        // 1. Check if the user making the request is an admin (this will be done through a middleware)
        //

        $request->validate([
            'reason' => 'required|string'
        ]);

        $block_reason = $request->input('reason');

        $user = User::where('username', '=', $username)->get()[0];

        if (!$user->is_app_banned()) {
            AppBan::create([
                'reason' => $block_reason,
                'admin_id' => 4,
                'banned_user_id' => $user->id
            ]);
        }

        return redirect('/admin/user');
    }

    /**
     * Shows to an admin a page where the admin can give a reason for the block
     */
    public function show_block_user(String $username)
    {
        // Verify if the person who is doing this is an admin

        $user_to_block = User::where('username', '=', $username)->get()[0];

        return view('pages.block_user', ['user' => $user_to_block]);
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

    /**
     * This should be used inside a transaction
     *
     * @$user_id The id of the user we want to delete
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
}
