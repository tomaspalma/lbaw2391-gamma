<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class FeedController extends Controller
{
    public function show_personal() {
        return view('pages.homepage', [
            'feed' => 'personal',
            'posts' => []
        ]);
    }

    public function show_popular() {
//> Post::withCount('reactions')->where('is_private', '=', false)->where('group_id', '=', null)->orderBy('reactions_count', 'desc')->get();
        $posts = Post::withCount('reactions')->where('is_private', '=', false)->orderBy('reactions_count', 'desc')->get();

        return view('pages.homepage', [
            'feed' => 'popular',
            'posts' => $posts
        ]);
    }
}
