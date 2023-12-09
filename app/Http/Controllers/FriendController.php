<?php

namespace App\Http\Controllers;

use App\Events\FriendRequest as FriendRequestEvent;
use App\Models\User;
use App\Models\FriendRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function show_friends(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        if (!$request->user()->is_friend($user) && $user->id !== Auth::id()) {
            abort(403, 'User is not your friend.');
        }

        $friends = $user->friends()->get();

        $friendRequests = $user->received_pending_friend_requests()->get();

        return view('pages.friends', [
            'user' => $user,
            'friends' => $friends,
            'tab' => 'friends',
            'friendRequests' => $friendRequests
        ]);
    }

    public function show_friend_requests(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $friendRequests = $user->received_pending_friend_requests()->get();

        return view('pages.friends', ['user' => $user, 'friendRequests' => $friendRequests, 'tab' => 'requests']);
    }

    public function add_friend_request(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        if ($request->user()->is_friend($user)) {
            return response()->json([
                'message' => 'User is already your friend.'
            ], 400);
        }
        FriendRequest::create([
            'user_id' => Auth::id(),
            'friend_id' => $user->id
        ]);

        return event(new FriendRequestEvent($user, $request->user(), null));
    }

    public function remove_friend(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        if (!$request->user()->is_friend($user)) {
            return response()->json([
                'message' => 'User is not your friend.'
            ], 404);
        }

        if ($request->user()->is_friend($user)) {
            DB::table('friends')
                ->where('friend1', '=', Auth::id())
                ->where('friend2', '=', $user->id)
                ->orWhere('friend1', '=', $user->id)
                ->where('friend2', '=', Auth::id())
                ->delete();
        }
    }

    public function remove_friend_request(Request $request, User $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        if (!$request->user()->is_friend($user)) {
            return response()->json([
                'message' => 'User is not your friend.'
            ], 404);
        }

        $friendRequest = FriendRequest::where('user_id', Auth::id())
            ->where('friend_id', $user->id)->where('is_accepted', null)
            ->first();

        if ($friendRequest == null) {
            return response()->json([
                'message' => 'Friend request does not exist.'
            ], 404);
        }

        $friendRequest->delete();

        return response()->json([
            'message' => 'Friend request removed.'
        ], 200);
    }

    public function accept_friend_request(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        FriendRequest::where('user_id', $user->id)
            ->where('friend_id', Auth::id())
            ->where('is_accepted', null)
            ->update(['is_accepted' => true]);

        return event(new FriendRequestEvent($request->user(), $user, true));
    }

    public function decline_friend_request(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        FriendRequest::where('user_id', $user->id)
            ->where('friend_id', Auth::id())
            ->where('is_accepted', null)
            ->update(['is_accepted' => false]);

        return event(new FriendRequestEvent($request->user(), $user, false));
    }

}
