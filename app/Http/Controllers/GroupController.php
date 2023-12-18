<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupRequest;
use App\Models\GroupOwner;
use App\Models\GroupUser;
use App\Models\User;
use App\Models\GroupBan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function showGroupForm(Request $request, string $id)
    {
        $group = Group::findOrFail($id);
        $posts = $group->posts()->paginate(10);
        $members = $group->all_users();
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

        if (!Auth::check()) {
            abort(401, 'Unauthorized: Authentication required.');
        }

        $groupsNormal = $user->groups('normal');
        $groupsOwner = $user->groups('owner');

        return view('pages.groups', ['feed' => 'groups', 'groupsNormal' => $groupsNormal, 'groupsOwner' => $groupsOwner]);
    }

    public function showGroupRequests(Request $request){
        $user = Auth::user();

        if (!Auth::check()) {
            abort(401, 'Unauthorized: Authentication required.');
        }

        else{
            $requests = $user->groupRequests();
            return view('pages.groups', ['feed' => 'requests', 'requests' => $requests]);
        }
    }

    public function banGroupMember(Request $request, int $id, string $username)

    {
        $owner = Auth::user();

        $rules = array('reason' => 'required|string');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()), 400); // 400 being the HTTP code for an invalid request.
        }

        $group = Group::find($id);
        $user = User::where('username', $username)->get()[0];

        $this->authorize('can_modify', $group);

        DB::transaction(function () use ($request, $user, $group) {
            if ($user->is_owner($group->id)) {
                GroupOwner::where('user_id', $user->id)->delete();
            } else {
                GroupUser::where('user_id', $user->id)->delete();
            }

            GroupBan::create([
                'reason' => $request->input('reason'),
                'group_owner_id' => Auth::user()->id,
                'banned_user_id' => $user->id,
                'group_id' => $group->id
            ]);
        });
    }

    public function promoteUser(Request $request, int $id, string $username)
    {

        $group = Group::find($id);
        $user = User::where('username', $username)->get()[0];

        $this->authorize('can_modify', $group);

        DB::transaction(function () use ($id, $user) {
            GroupUser::where('user_id', $user->id)->get()[0]->delete();

            GroupOwner::create([
                'group_id' => $id,
                'user_id' => $user->id
            ]);
        });
    }

    public function showGroupMembers(Request $request, int $id, string $filter = null)
    {
        $group = Group::findOrFail($id);

        $members = $group->all_users();
        $posts = $group->posts();
        $user = Auth::user();

        if ($request->is("api*")) {
            $userCards = [];

            if ($filter === 'groupOwners') {
                $members = $group->group_owners()->get();
            } elseif ($filter === 'members') {
                $members = $group->users()->get();
            }

            for ($i = 0; $i < count($members); $i++) {
                $userCards[] = view('partials.user_card', ['user' => $members[$i], 'adminView' => false, 'is_group' => true, 'group' => $group])->render();
            }

            return $userCards;
        } else {
            return view('pages.group', [
                'feed' => 'members',
                'members' => $members,
                'posts' => $posts,
                'group' => $group,
                'user' => $user
            ]);
        }
    }

    public function addToGroup(string $id)
    {

        $group = Group::findOrFail($id);

        $user = Auth::user();

        if (!Auth::check()){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: Authentication required.'
                ]
            ], 401);
        }

        if (!$group->is_private) {
            $group->users()->attach($user->id);
            return response()->json([
                'message' => 'User added to the group successfully', 'new_color' => 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded',
                'new_text' => 'Leave Group', 'new_method' => 'delete',
                'new_action' => 'leave'
            ]);
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

            return response()->json([
                'message' => 'User asked to enter the group successfully', 'new_color' => 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded',
                'new_text' => 'Remove Request', 'new_method' => 'delete',
                'new_action' => 'removeRequest'
            ]);
        }
    }

    public function removeToGroup(string $id)
    {
        $user = Auth::user();
        if (!Auth::check()){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: Authentication required.'
                ]
            ], 401);
        }

        if(!$user->in_group($id)){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: User not in the group.'
                ]
            ], 401);
        }

        try {
            $group = Group::findOrFail($id);

            $user = Auth::user();

            DB::transaction(function () use ($user, $group) {
                DB::table('group_user')
                    ->where('user_id', $user->id)
                    ->where('group_id', $group->id)
                    ->delete();
            });

            return response()->json([
                'message' => 'User removed from the group successfully', 'new_color' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded',
                'new_text' => 'Enter this group', 'new_method' => 'post',
                'new_action' => 'enter'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error removing user from the group', 'message' => $e->getMessage()], 500);
        }
    }

    public function removeRequest(string $id)
    {
        $user = Auth::user();

        $group = Group::findOrFail($id);
        if (!Auth::check()){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: Authentication required.'
                ]
            ], 401);
        }

        if(!$user->is_pending($id)){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: User do not have a pending request'
                ]
            ], 401);
        }


        $group->remove_request($user->id);

        return response()->json([
            'message' => 'User removed from the group successfully', 'new_color' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded',
            'new_text' => 'Enter this group', 'new_method' => 'post',
            'new_action' => 'enter'
        ]);
    }

    public function edit(string $id)
    {
        $group = Group::findOrFail($id);

        $this->authorize('edit', $group);

        return view('pages.edit_group', [
            'group' => $group
        ]);
    }

    public function update(Request $request, string $id)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|required|string',
            'privacy' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $group = Group::findOrFail($id);

        $this->authorize('edit', $group);

        $sameName = Group::where('name', $request->input('name'))->get();

        if (count($sameName) > 0) {
            if ($sameName[0]->id != $id) {
                return redirect()->back()->withErrors(['name' => 'This name is already taken.']);
            }
        }

        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->is_private = $request->input('privacy') === 'private';

        if ($request->hasFile('image')) {
            FileController::upload($request->file('image'), 'group', $group->id);
        }

        if ($request->hasFile('banner')) {
            FileController::upload($request->file('banner'), 'group_banner', $group->id);
        }

        $group->save();

        return redirect()->route('groupPosts', $group->id)->with('success', 'Group updated successfully.');

    }

    public function declineRequest(string $id){
        
        $groupRequest = GroupRequest::findOrFail($id);


        $user = Auth::user();
        if (!Auth::check()){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: Authentication required.'
                ]
            ], 401);
        }

        if(!$user->is_owner($groupRequest->group->id)){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: Not a Group Owner',
                    'temp' => $groupRequest
                ]
            ], 401);
        }

        $groupRequest->decline();

        return redirect("/groups/requests/");
    }

    public function approveRequest(string $id){

        $groupRequest = GroupRequest::findOrFail($id);
        
        $user = Auth::user();
        if (!Auth::check()){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: Authentication required.'
                ]
            ], 401);
        }

        if(!$user->is_owner($groupRequest->group->id)){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: Not a Group Owner'
                ]
            ], 401);
        }

        $grouprequest = GroupRequest::findOrFail($id);

        $grouprequest->approve();

        return redirect("/groups/requests/");
    }

}
