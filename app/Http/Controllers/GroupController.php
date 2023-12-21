<?php

namespace App\Http\Controllers;

use App\Events\GroupRequestNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupRequest;
use App\Models\GroupOwner;
use App\Models\GroupUser;
use App\Models\GroupInvite;
use App\Models\User;
use App\Models\GroupBan;
use Doctrine\DBAL\Schema\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function showGroupOwnerGroupInvites() {
        $user = Auth::user();

        $groupsNormal = $user->groups('normal');
        $groupsOwner = $user->groups('owner');
        $requests = $user->groupRequests();

        $invites = $user->groupInvites();


        return view('pages.groups', ['feed' => 'invites', 'invites' => $invites, 'groupsNormal' => $groupsNormal, 'groupsOwner' => $groupsOwner, 'requests' => $requests]);
    }

    public function acceptInvite(int $id, string $username) {
        $user = User::where('username', $username)->firstOrFail();
        $group = Group::findOrFail($id);

        $group_invite = GroupInvite::where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->where('is_accepted', false)->firstOrFail();

        $group_invite->is_accepted = true;
        $group_invite->save();
    }

    public function rejectInvite(int $id, string $username) {
        $user = User::where('username', $username)->firstOrFail();
        $group = Group::findOrFail($id);
        
        GroupInvite::where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->where('is_accepted', false)->delete();
    }

    public function showGroup(Request $request, string $id)
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

        $groupsNormal = $user->groups('normal');
        $groupsOwner = $user->groups('owner');
        $requests = $user->groupRequests();

        return view('pages.groups', ['feed' => 'groups', 'groupsNormal' => $groupsNormal, 'groupsOwner' => $groupsOwner, 'requests' => $requests]);
    }

    public function showGroupRequests(Request $request){
        $user = Auth::user();

        $groupsNormal = $user->groups('normal');
        $groupsOwner = $user->groups('owner');
        $requests = $user->groupRequests();
        return view('pages.groups', ['feed' => 'requests', 'requests' => $requests, 'groupsNormal' => $groupsNormal, 'groupsOwner' => $groupsOwner]);

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

        if (!$group->is_private) {
            $group->users()->attach($user->id);
            return response()->json([
                'message' => 'User added to the group successfully', 'new_color' => 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded',
                'new_text' => 'Leave Group', 'new_method' => 'delete',
                'new_action' => 'leave',
                'sum' => '1',
            ]);
        } else {
            
            
            DB::table('group_request')->insert([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'is_accepted' => false,
                'date' => now(),
            ]);

            $id = DB::select('SELECT id FROM group_request ORDER BY id DESC LIMIT 1')[0]->id;

            $groupRequest = GroupRequest::findOrFail($id);

            event(new GroupRequestNotification($user->id, $groupRequest, false));

            return response()->json([
                'message' => 'User asked to enter the group successfully', 'new_color' => 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded',
                'new_text' => 'Remove Request', 'new_method' => 'delete',
                'new_action' => 'removeRequest',
                'sum' => '0'
            ]);
        }
    }

    public function removeToGroup(string $id)
    {
        $user = Auth::user();
        $group = Group::findOrFail($id);

        
        if(!$user->in_group($group)){
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized: User not in the group.'
                ]
            ], 401);
        }
        

        try {

            $user = Auth::user();
            
            if(!$user->is_owner($group->id)){
                DB::transaction(function () use ($user, $group) {
                    DB::table('group_user')
                        ->where('user_id', $user->id)
                        ->where('group_id', $group->id)
                        ->delete();
                });
            }

            else{
                DB::transaction(function () use ($user, $group) {
                    DB::table('group_owner')
                        ->where('user_id', $user->id)
                        ->where('group_id', $group->id)
                        ->delete();
                });
            }

            return response()->json([
                'message' => 'User removed from the group successfully', 'new_color' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded',
                'new_text' => 'Enter this group', 'new_method' => 'post',
                'new_action' => 'enter',
                'sum' => '-1'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error removing user from the group', 'message' => $e->getMessage()], 500);
        }
    }

    public function removeRequest(string $id)
    {
        $user = Auth::user();

        $group = Group::findOrFail($id);

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
            'message' => 'Request deleted successfully', 'new_color' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded',
            'new_text' => 'Enter this group', 'new_method' => 'post',
            'new_action' => 'enter',
            'sum' => '0'
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

        return response()->json([
            'message' => 'Request declined successfully'
        ]);
    }

    public function approveRequest(string $id){

        $groupRequest = GroupRequest::findOrFail($id);
        
        $user = Auth::user();

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

        event(new GroupRequestNotification($grouprequest->user->id, $groupRequest, true));

        return response()->json([
            'message' => 'Request accepted successfully'
        ]);
    }


    public function showCreateForm()
    {
        $this->authorize('create', Group::class);

        return view('pages.create_group', [
            'bannerImage' => FileController::defaultAsset('group_banner'),
            'groupImage' => FileController::defaultAsset('group')
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Group::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'privacy' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $sameName = Group::where('name', $request->input('name'))->get();

        if (count($sameName) > 0) {
            return redirect()->back()->withErrors(['name' => 'This name is already taken.']);
        }

        $group = null;
        DB::transaction(function () use ($request, &$group) {
            $group = Group::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_private' => $request->privacy
            ]);
    
            GroupOwner::create([
                'group_id' => $group->id,
                'user_id' => Auth::user()->id
            ]);
        });

        if ($request->hasFile('image')) {
            FileController::upload($request->file('image'), 'group', $group->id);
        }

        if ($request->hasFile('banner')) {
            FileController::upload($request->file('banner'), 'group_banner', $group->id);
        }

        return redirect()->route('groupPosts', $group->id);
    }

    public function checkGroupNameExists(string $group_name)
    {
        $group = Group::where('name', $group_name)->get();

        if(count($group) > 0) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    public function showInviteForm(Request $request, string $id)
    {
        $group = Group::findOrFail($id);

        $users = $group->not_users();

        return view('pages.invite_users', ['group' => $group, 'feed' => 'invite', 'users' => $users]);
    }

    public function searchUsersCanBeInvited(int $id, string $query = null) {
        $group = Group::find($id);

        $groupUserIds = $group->users()->pluck('users.id');
        $groupOwnerIds = $group->owners()->pluck('users.id');
        $groupInviteIds = $group->group_invites()->pluck('user_id');
        
        $users = null;

        if($query === null) {
            $users = User::whereNotIn('users.id', $groupUserIds)
                ->whereNotIn('users.id', $groupOwnerIds)
                ->whereNotIn('users.id', $groupInviteIds)
                ->where('users.id', '<>', '0')
                ->paginate(15);
        } else {
            $users = User::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                ->whereNotIn('users.id', $groupUserIds)
                ->whereNotIn('users.id', $groupOwnerIds)
                ->whereNotIn('users.id', $groupInviteIds)
                ->where('users.id', '<>', '0')
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                ->paginate(15);
        }
        
        $userCards = [];

        foreach ($users as $user) {
            $userCards[] = view('partials.user_card', ['user' => $user, 'adminView' => false, 'is_group' => false, 'group' => $group, 'invite' => true])->render();
        }

        return response()->json($userCards);
    }

    public function showSentPendingInvites(string $id){
        $group = Group::findOrFail($id);

        $invites = $group->pending_invites()->paginate(10);

        return view('pages.invite_users', ['group' => $group, 'feed' => 'invited', 'invites' => $invites]);
    }

    public function inviteUser(Request $request, int $id, string $username)
    {
        $group = Group::find($id);
        $user = User::where('username', $username)->firstOrFail();
        
        $this->authorize('invite', [$group, $user]);

        GroupInvite::create([
            'owner_id' => Auth::user()->id,
            'user_id' => $user->id,
            'group_id' => $id,
            'is_accepted' => false
        ]);
    }
}
