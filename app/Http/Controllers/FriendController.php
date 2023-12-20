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

        $this->authorize('view_friends', $user);

        $friends = $user->friends()->paginate(10);

        $friendRequests = $user->received_pending_friend_requests()->get();

        if ($request->is("api*")) {
            $friendCards = [];
            foreach ($friends as $friend) {
                $friendCards[] = view('partials.user_card', ['user' => $friend, 'adminView' => false])->render();
            }

            return response()->json($friendCards);
        } else {
            return view('pages.friends', [
                'user' => $user,
                'friends' => $friends,
                'tab' => 'friends',
                'friendRequests' => $friendRequests
            ]);
        }
    }

    public function show_friend_requests(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('view_friend_requests', $user);

        $friendRequests = $user->received_pending_friend_requests()->paginate(12);

        return view('pages.friends', ['user' => $user, 'friendRequests' => $friendRequests, 'tab' => 'requests']);
    }

    public function show_friend_request_cards(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('view_friend_requests', $user);

        $friendRequests = $user->received_pending_friend_requests()->paginate(12);
        
        $cards = [];
        foreach ($friendRequests as $friendRequest) {
            $cards[] = view('partials.friend_requests_card', ['request' => $friendRequest])->render();
        }

        return response()->json($cards);
    }

    public function send_friend_request(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('send_friend_request', $user);

        FriendRequest::create([
            'user_id' => Auth::id(),
            'friend_id' => $user->id
        ]);

        return event(new FriendRequestEvent($user, $request->user(), null));
    }

    public function remove_friend(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('remove_friend', $user);

        if ($request->user()->is_friend($user)) {
            DB::table('friends')
                ->where('friend1', '=', Auth::id())
                ->where('friend2', '=', $user->id)
                ->orWhere('friend1', '=', $user->id)
                ->where('friend2', '=', Auth::id())
                ->delete();
        }
    }

    public function cancel_friend_request(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('cancel_friend_request', $user);

        $friendRequest = FriendRequest::where('user_id', Auth::id())
            ->where('friend_id', $user->id)->where('is_accepted', null)
            ->first();

        $friendRequest->delete();

        return response()->json([
            'message' => 'Friend request removed.'
        ], 200);
    }

    public function accept_friend_request(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('accept_friend_request', $user);

        FriendRequest::where('user_id', $user->id)
            ->where('friend_id', Auth::id())
            ->where('is_accepted', null)
            ->update(['is_accepted' => true]);

        return event(new FriendRequestEvent($request->user(), $user, true));
    }

    public function decline_friend_request(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $this->authorize('decline_friend_request', $user);

        FriendRequest::where('user_id', $user->id)
            ->where('friend_id', Auth::id())
            ->where('is_accepted', null)
            ->update(['is_accepted' => false]);

        return event(new FriendRequestEvent($request->user(), $user, false));
    }
}
