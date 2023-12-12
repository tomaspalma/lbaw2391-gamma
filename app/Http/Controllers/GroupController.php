<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function showGroupForm(Request $request, string $id)
    {
        $group = Group::findOrFail($id);
        $posts = $group->posts()->paginate(5);
        $members = $group->users();
        $user = Auth::user();

        if ($request->is("api*")) {
            $this->authorize('viewPostsAndMembers', $group);

            $postCards = [];

            foreach ($posts as $post) {
                $postCards[] = view('partials.post_card', ['post' => $post, 'preview' => false])->render();
            }

            return response()->json($postCards);
        } else {
            return view('pages.group', [
                'feed' => 'posts',
                'posts' => $posts,
                'group' => $group,
                'members' => $members,
                'preview' => false,
                'user' => $user
            ]);
        }
    }

    public function showGroupMembers(string $id): View
    {

        $group = Group::findOrFail($id);
        $members = $group->users();
        $posts = $group->posts();
        $user = Auth::user();
        return view('pages.group', [
            'feed' => 'members',
            'members' => $members,
            'posts' => $posts,
            'group' => $group,
            'user' => $user
        ]);
    }

    public function addToGroup(string $id)
    {

        $group = Group::findOrFail($id);

        $user = Auth::user();

        if (!$group->is_private) {
            $group->users()->attach($user->id);
            return redirect("/group/$id");
        } else {

            $last_id = DB::select('SELECT id FROM group_request ORDER BY id DESC LIMIT 1')[0]->id;
            $new_id = $last_id + 1;
            DB::table('group_request')->insert([
                'id' => $new_id,
                'user_id' => $user->id,
                'group_id' => $group->id,
                'is_accepted' => false,
                'date' => now(),
            ]);

            return redirect("/group/$id");
        }
    }

    public function removeToGroup(string $id)
    {

        $group = Group::findOrFail($id);

        $user = Auth::user();

        DB::transaction(function () use ($user, $group) {
            DB::table('group_user')
                ->where('user_id', $user->id)
                ->where('group_id', $group->id)
                ->delete();
        });

        //return redirect("/group/$id");

    }

    public function removeRequest(string $id)
    {

        $group = Group::findOrFail($id);

        $user = Auth::user();

        DB::transaction(function () use ($user, $group) {
            DB::table('group_request')
                ->where('user_id', $user->id)
                ->where('group_id', $group->id)
                ->delete();
        });

        return redirect("/group/$id");
    }
}
