<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Grouprequest;
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

    public function showGroupsForm(Request $request){
        $user = Auth::user();
        $groupsNormal = $user->groups('normal');
        $groupsOwner = $user->groups('owner');

        return view('pages.groups', ['feed' => 'groups', 'groupsNormal' => $groupsNormal, 'groupsOwner' => $groupsOwner]);
    }

    public function showGroupRequests(Request $request){
        $user = Auth::user();

        $requests = $user->groupRequests();
        return view('pages.groups', ['feed' => 'requests', 'requests' => $requests]);
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
            return response()->json(['message' => 'User added to the group successfully', 'new_color' => 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded', 
            'new_text' => 'Leave Group', 'new_method' => 'delete']);
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

            return response()->json(['message' => 'User asked to enter the group successfully', 'new_color' => 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded', 
            'new_text' => 'Remove Request', 'new_method' => 'delete']);
        }
    }

    public function removeToGroup(string $id)
    {
        try {
            $group = Group::findOrFail($id);
    
            $user = Auth::user();
    
            DB::transaction(function () use ($user, $group) {
                DB::table('group_user')
                    ->where('user_id', $user->id)
                    ->where('group_id', $group->id)
                    ->delete();
            });
    
            return response()->json(['message' => 'User removed from the group successfully', 'new_color' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded', 
                'new_text' => 'Enter this group', 'new_method' => 'post']);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error removing user from the group', 'message' => $e->getMessage()], 500);
        }
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



        return response()->json(['message' => 'User removed from the group successfully', 'new_color' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded', 
                'new_text' => 'Enter this group', 'new_method' => 'post']);

    }

    public function declineRequest(string $id){

        DB::transaction(function () use ($id) {
            DB::table('group_request')
                ->where('id', $id)
                ->delete();
        });
        return redirect("/groups/requests/");
    }

    public function approveRequest(string $id){
        $grouprequest = GroupRequest::findOrFail($id);

        $grouprequest->approve();

        return redirect("/groups/requests/");
    }

}
