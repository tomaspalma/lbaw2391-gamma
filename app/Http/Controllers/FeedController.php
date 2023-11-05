<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    public function show_personal()
    {
        /*
        * Posts made by friends and groups they belong
        *
        */

        // TODO Alter this so we get the logged in user
        $user = User::find(1);

        $groups = $user->groups;

        $posts = $groups->pluck('posts')->flatten();

        $friends = $user->friends;
        foreach ($friends as $friend) {
            $friend_posts = $friend->posts;
            foreach ($friend_posts as $post) {
                $posts[] = $post;
            }
        }


        return view('pages.homepage', [
            'feed' => 'personal',
            'posts' => $posts
        ]);
    }

    public function show_popular()
    {
        //> Post::withCount('reactions')->where('is_private', '=', false)->where('group_id', '=', null)->orderBy('reactions_count', 'desc')->get();
        $posts = Post::withCount('reactions')->where('is_private', '=', false)->orderBy('reactions_count', 'desc')->get();

        return view('pages.homepage', [
            'feed' => 'popular',
            'posts' => $posts
        ]);
    }
}
