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
        return view('pages.search', [
            'query' => $query,
            'hidden' => false,
            'previewMenuShadow' => false,
            'previewMenuWidth' => 'w-full',
            'previewMenuPosAbs' => false,
            'previewMenuName' => 'main-search',
            'toggled' => $request->input('toggled')
        ]);
    }


    public function fullTextGroups(Request $request, string $query = null)
    {
        $groups = [];

        if ($query === null) {
            $groups = Group::where('is_private', '<>', false)->get();
        } else {
            $groups = Group::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->get();
        }

        return response()->json($groups);
    }

    /**
     * Method used in the API endpoint used in the admin page to search for users
     */
    public function adminFullTextUsers(string $query = null)
    {
        if ($query === null) {
            $users = User::where('id', '<>', 0)->where('role', '<>', 1)->get();
        } else {
            $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->where('id', '<>', 0)
                ->where('role', '<>', 1)
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->get();
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
                    ->get();
            } else {
                $users = User::where('id', '<>', 0)
                    ->where('is_private', '=', false)
                    ->get();
            }

            $usersJson = [];
            for ($i = 0; $i < count($users); $i++) {
                $usersJson[] = new UserResource($users[$i]);
            }

            return response()->json($usersJson);
        } else {
            $users = [];

            $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->where('is_private', '=', false)
                ->where('id', '<>', 0)
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->get();

            $usersJson = [];
            for ($i = 0; $i < count($users); $i++) {
                $usersJson[] = new UserResource($users[$i]);
            }

            return response()->json($usersJson);
        }
    }

    public function fullTextPosts(Request $request, string $query = null)
    {
        $rawPosts = [];

        if ($query === null) {
            $rawPosts = Post::where('is_private', '<>', false)->get();
        } else {
            $rawPosts = Post::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) and not is_private', [$query])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->get();
        }

        $finalPosts = [];
        for ($i = 0; $i < count($rawPosts); $i++) {
            $finalPosts[] = new PostResource($rawPosts[$i]);
        }

        return response()->json($finalPosts);
    }
}
