<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Group;
use App\Models\Post;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    private $pagination_limits = [
        'posts' => 10
    ];

    public function showSearch(Request $request, string $query = null)
    {
        $toggled = $request->input('toggled');

        $entities = null;
        switch ($toggled) {
            case 'users':
                $entities = $this->fullTextUsers($request, $query);
                break;
            case 'posts':
                $entities = $this->fullTextPosts($request, $query, false);
                break;
            case 'groups':
                $entities = $this->fullTextGroups($request, $query);
                break;
        }

        if ($request->is("api*")) {
            return $entities;
        } else {
            return view('pages.search', [
                'query' => $query,
                'hidden' => false,
                'previewMenuShadow' => false,
                'previewMenuWidth' => 'w-full',
                'previewMenuPosAbs' => false,
                'previewMenuName' => 'main-search',
                'toggled' => $toggled,
                'isMobile' => false,
                'entities' => $entities
            ]);
        }
    }


    public function fullTextGroups(Request $request, string $query = null)
    {
        $groups = [];

        if ($query === null) {
            $groups = Group::where('is_private', false)->paginate(10);
        } else {
            $groups = Group::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->paginate(10);
        }

        if ($request->ajax()) {
            return response()->json($groups);
        } else {
            return $groups;
        }
    }

    /**
     * Method used in the API endpoint used in the admin page to search for users
     */
    public function adminFullTextUsers(string $query = null)
    {
        if ($query === null) {
            $users = User::where('id', '<>', 0)->where('role', '<>', 1)->paginate(15);
        } else {
            $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->where('id', '<>', 0)
                ->where('role', '<>', 1)
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->paginate(15);
        }

        $userCardsJson = [];
        for ($i = 0; $i < count($users); $i++) {
            // @include('partials.user_card', [ 'user'=> $appeal->appban->user, 'adminView' => true, 'appealView' => true])
            $userCardsJson[] = view('partials.user_card', ['user' => new UserResource($users[$i]), 'adminView' => true])->render();
        }

        return response()->json($userCardsJson);
    }

    public function fullTextUsers(Request $request, string $query = null)
    {
        if ($query === null) {
            $users = [];

            // Public user
            if (Auth::user() === null) {
                $users = User::where('id', '<>', 0)
                    ->where('role', '<>', 1)
                    ->where('is_private', '=', false)
                    ->paginate(10);
            } else {
                $users = User::where('id', '<>', 0)
                    ->where('is_private', '=', false)
                    ->paginate(10);
            }

            if ($request->is("api*")) {
                $usersJson = [];
                foreach ($users as $user) {
                    $usersJson[] = view('partials.user_card', ['user' => $user, 'adminView' => false])->render();
                }

                return response()->json($usersJson);
            } else {
                return $users;
            }
        } else {
            $users = [];

            $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->where('is_private', '=', false)
                ->where('id', '<>', 0)
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->paginate(15);

            if ($request->is('api*')) {
                $usersJson = [];
                foreach ($users as $user) {
                    $usersJson[] = view('partials.user_card', ['user' => $user, 'adminView' => false])->render();
                }

                return response()->json($usersJson);
            } else {
                return $users;
            }
        }
    }

    public function fullTextPosts(Request $request, string $query = null, bool $api = true)
    {
        $rawPosts = [];

        if ($query === null) {
            $rawPosts = Post::where('is_private', '<>', false)->paginate($this->pagination_limits['posts']);
        } else {
            $rawPosts = Post::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) and not is_private', [$query])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->paginate($this->pagination_limits['posts']);
        }

        if ($request->is('api*')) {
            $post_cards = [];
            foreach ($rawPosts as $post) {
                $post_cards[] = view('partials.post_card', ['post' => new PostResource($post), 'preview' => true])->render();
            }

            return response()->json($post_cards);
        } else {
            return $rawPosts;
        }
    }
}
