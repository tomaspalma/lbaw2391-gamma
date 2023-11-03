<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Group;
use App\Models\Post;

class SearchController extends Controller
{
    public function showSearch(string $query=null) {
        return view('pages.search', [
            'query' => $query
        ]);
    }

    public function searchUsers(string $query) {
        if($query === "hello") {
            return view('pages.homepage', [
                'feed' => 'personal',
                'posts' => []
            ]);
        } 
    }

    public function fullTextGroups(Request $request, string $query) {
        $groups = Group::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
            ->get();

        return response()->json($groups);
    }

    public function fullTextUsers(Request $request, string $query) {
        $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
            ->get();

        return response()->json($users);
    }

    public function fullTextPosts(Request $request, string $query) {
        $posts = Post::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
            ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
            ->get();

        return response()->json($posts);
    }

}
