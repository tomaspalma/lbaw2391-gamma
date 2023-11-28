<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class FeedController extends Controller
{
    public function show_personal(Request $request)
    {
        if (!$request->user()->has_verified_email()) {
            return view('pages.homepage', [
                'feed' => 'personal',
                'posts' => [],
                'email_verified' => false
            ]);
        }
        /*
         * Posts made by friends and groups they belong
         *
         */

        $user = auth()->user();

        $groups = $user->groups;
        $posts = $groups->pluck('posts')->flatten();

        $friends = $user->friends;
        $posts = $posts->merge($friends->pluck('posts')->flatten());
        $posts = Post::withCount('reactions')->whereIn('id', $posts->pluck('id'))->get();

        $posts = $posts->unique('id')->values();

        return view('pages.homepage', [
            'feed' => 'personal',
            'posts' => $posts,
            'email_verified' => true
        ]);
    }

    public function show_popular()
    {
        $posts = Post::withCount('reactions')
            ->where('is_private', '=', false)
            ->orderBy('reactions_count', 'desc')
            ->get();

        return view('pages.homepage', [
            'feed' => 'popular',
            'posts' => $posts,
            'email_verified' => true
        ]);
    }
}
