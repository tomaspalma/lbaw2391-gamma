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


        $personal_posts = Post::where(function ($query) use ($user) {
            $query->whereNull('group_id')
                ->whereIn('author', function ($query) use ($user) {
                    $query->select('friend2')
                        ->from('friends')
                        ->where('friend1', $user->id)
                        ->unionAll(function ($query) use ($user) {
                            $query->select('friend1')
                                ->from('friends')
                                ->where('friend2', $user->id);
                        });
                })
                ->orWhereIn('group_id', function ($query) use ($user) {
                    $query->select('group_id')
                        ->from('group_user')
                        ->where('user_id', $user->id)
                        ->unionAll(function ($query) use ($user) {
                            $query->select('group_id')
                                ->from('group_owner')
                                ->where('user_id', $user->id);
                        });
                });
        })
            ->where('author', '<>', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(10);


        if ($request->is("api*")) {
            $post_cards = [];
            foreach ($personal_posts as $post) {
                $post_cards[] = view('partials.post_card', ['post' => $post, 'preview' => false])->render();
            }

            return response()->json($post_cards);
        } else {
            return view('pages.homepage', [
                'feed' => 'personal',
                'posts' => $personal_posts,
                'email_verified' => true
            ]);
        }
    }

    public function show_popular(Request $request)
    {
        $raw_posts = [];

        $user = auth()->user();

        $raw_posts = Post::withCount('reactions')
            ->where(function ($query) {
                $query->where('is_private', '=', false)
                    ->whereHas('owner', function ($query) {
                        $query->where('is_private', '=', false);
                    })
                    ->where('group_id', '=', null);
            })
            ->orWhereHas('group', function ($query) {
                $query->where('is_private', '=', false);
            })
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
