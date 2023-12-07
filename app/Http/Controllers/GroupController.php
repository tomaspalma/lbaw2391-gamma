<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
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
        $posts = $group->posts();
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

    public function banGroupMember(Request $request, int $id, string $username)
    {
        $rules = array('reason' => 'required|string');
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()), 400); // 400 being the HTTP code for an invalid request.
        }

        $group = Group::find($id);
        $user = User::where('username', $username)->get()[0];

        $this->authorize('can_modify', $group);

        DB::transaction(function () use ($request, $user, $group) {
            if ($user->is_owner($group)) {
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
            return redirect("/group/$id");
        } else {

            $last_id = DB::select('SELECT id FROM users ORDER BY id DESC LIMIT 1')[0]->id;
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
