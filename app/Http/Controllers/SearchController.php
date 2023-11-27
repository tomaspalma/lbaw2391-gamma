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
    public function showSearch(Request $request, string $query = null)
    {
        $toggled = $request->input('toggled');

        $entities = null;
        switch($toggled) {
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


    public function fullTextGroups(Request $request, string $query = null)
    {
        $groups = [];

        if ($query === null) {
            $groups = Group::where('is_private', '<>', false)->paginate(10);
        } else {
            $groups = Group::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->paginate(10);
        }

        if($request->ajax()) {
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

        $usersJson = [];
        for ($i = 0; $i < count($users); $i++) {
            $usersJson[] = new UserResource($users[$i]);
        }

        return response()->json($usersJson);
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

            if($request->ajax()) {
                $usersJson = [];
                for ($i = 0; $i < count($users); $i++) {
                    $usersJson[] = new UserResource($users[$i]);
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
            
            if($request->ajax()) {
                $usersJson = [];
                for ($i = 0; $i < count($users); $i++) {
                    $usersJson[] = new UserResource($users[$i]);
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
            $rawPosts = Post::where('is_private', '<>', false)->paginate(10);
        } else {
            $rawPosts = Post::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) and not is_private', [$query])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->paginate(10);
        }
        
        if($api) {
            $post_cards = [];
            foreach($rawPosts as $post) {
                $post_cards[] = view('partials.post_card', ['post' => new PostResource($post), 'preview' => true])->render(); 
            }

            return response()->json($post_cards);
        } else {
            return $rawPosts;
        }
    }
}

/*
 ArgumentCountError: Too few arguments to function App\Http\Controllers\SearchController::fullTextPosts(), 2 passed in /home/tomaspalma/lbaw2391/vendor/laravel/framework/src/Illuminate/Routing/Controller.php on line 54 and exactly 3 expected in file /home/tomaspalma/lbaw2391/app/Http/Controllers/SearchController.php on line 139
*/
