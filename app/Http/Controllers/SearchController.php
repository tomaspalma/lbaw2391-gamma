<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Group;
use App\Models\Post;

use App\Http\Resources\PostResource;
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


    public function fullTextGroups(Request $request, string $query)
    {
        $groups = Group::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
            ->get();

        return response()->json($groups);
    }

    public function fullTextUsers(Request $request, string $query)
    {
        // $admin = Auth::user()->is_admin;
        $admin = false;

        if ($admin) {
        } else {
            $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->where('is_private', '=', false)
                ->where('id', '<>', 0)
                ->get();

            return response()->json($users);
        }
    }

    public function fullTextPosts(Request $request, string $query)
    {
        $rawPosts = Post::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) and not is_private', [$query])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
            ->get();

        $finalPosts = [];
        for ($i = 0; $i < count($rawPosts); $i++) {
            $finalPosts[] = new PostResource($rawPosts[$i]);
        }

        return response()->json($finalPosts);
    }
}
