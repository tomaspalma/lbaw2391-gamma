<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
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

        //$groups = $user->groups('owner');


        $raw_posts = Post::withCount('reactions')
            ->where('is_private', '=', true)
            ->where('group_id', '=', null)
            ->orderBy('reactions_count', 'desc')
            ->paginate(10);

        $friends = $user->friends;
        $raw_posts = $raw_posts->merge($friends->pluck('posts')->flatten());
        $raw_posts = Post::withCount('reactions')->whereIn('id', $raw_posts->pluck('id'))->paginate(10);

        if ($request->is("api*")) {
            $post_cards = [];
            foreach ($raw_posts as $post) {
                $post_cards[] = view('partials.post_card', ['post' => $post, 'preview' => false])->render();
            }

            return response()->json($post_cards);
        } else {
            return view('pages.homepage', [
                'feed' => 'personal',
                'posts' => $raw_posts,
                'email_verified' => true
            ]);
        }
    }

    public function show_popular(Request $request)
    {
        $raw_posts = [];

        $user = auth()->user();

        $raw_posts = Post::withCount('reactions')
            ->where('is_private', '=', false)
            ->where('group_id', '=', null)
            ->orderBy('reactions_count', 'desc')
            ->paginate(10);

        if ($request->is("api*")) {
            $post_cards = [];
            foreach ($raw_posts as $post) {
                $post_cards[] = view('partials.post_card', ['post' => $post, 'preview' => false])->render();
            }

            return response()->json($post_cards);
        } else {
            return view('pages.homepage', [
                'feed' => 'popular',
                'posts' => $raw_posts,
                'email_verified' => true
            ]);
        }
    }
}
