<?php

namespace App\Policies;

use App\Models\AppBan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function can_have_appeal_removed(User $user): Response
    {
        $appeal = $user->app_ban->appeal_model;

        return $appeal === null ? Response::allow() : Response::deny("User does not have any appeals");
    }

    /**
     * Determine whether or not the user has an app ban, e.g. if they have a ban to appeal from
     */
    public function can_appeal_appban(User $user): Response
    {
        $appban = AppBan::where('banned_user_id', $user->id)->get();

        return count($appban) > 0
            ? Response::allow()
            : Response::deny("You do not have any app bans.");
    }

    /**
     * Determine whether the user can update the profile.
     */
    public function update(User $user, User $model): response
    {
        return ($user->id === $model->id || $user->is_admin()) ? Response::allow() : Response::deny('You do not own this profile.');
    }

    /**
     * Determine whether the user can update the password of a model.
     */
    public function updatePassword(User $user, User $model): response
    {
        return $user->id === $model->id ? Response::allow() : Response::deny('You do not own this profile.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): response
    {
        return ($user->id === $model->id || $user->is_admin()) ? Response::allow() : Response::deny('You do not own this profile.');
    }

    public function view(User $user, User $model): response
    {
        return $user->is_friend($model) ? Response::allow() : Response::deny('You are not friends of the user.');
    }

    public function view_friends(User $user, User $model): Response
    {
        if ($user === null) {
            return Response::deny('You must be logged in to see friends.');
        }
        return $user->is_friend($model) || $user->id === $model->id ? Response::allow() : Response::deny('You are not friends of the user.');
    }

    public function view_friend_requests(User $user, User $model): Response
    {
        if ($user === null) {
            return Response::deny('You must be logged in to see friend requests.');
        }
        return $user->id === $model->id ? Response::allow() : Response::deny('You do not own this profile.');
    }

    public function send_friend_request(User $user, User $model): Response
    {
        if ($user === null) {
            return Response::deny('You must be logged in to send a friend request.');
        } else if ($user->id === $model->id) {
            return Response::deny('You cannot send a friend request to yourself.');
        } else if ($user->is_friend($model)) {
            return Response::deny('You are already friends with this user.');
        } else if ($user->has_friend_request_from($model)) {
            return Response::deny('You already have a friend request from this user.');
        } else if ($user->has_friend_request_to($model)) {
            return Response::deny('You already sent a friend request to this user.');
        } else {
            return Response::allow();
        }
    }
    public function cancel_friend_request(User $user, User $model): Response
    {
        if ($user === null) {
            return Response::deny('You must be logged in to cancel a friend request.');
        } else if ($user->id === $model->id) {
            return Response::deny('You cannot cancel a friend request to yourself.');
        } else if ($user->is_friend($model)) {
            return Response::deny('You are already friends with this user.');
        } else if ($user->has_friend_request_from($model)) {
            return Response::deny('You already have a friend request from this user.');
        } else if ($user->has_friend_request_to($model)) {
            return Response::allow();
        } else {
            return Response::deny('You do not have a friend request to this user.');
        }
    }

    public function accept_friend_request(User $user, User $model): Response
    {
        if ($user === null) {
            return Response::deny('You must be logged in to accept a friend request.');
        } else if ($user->id === $model->id) {
            return Response::deny('You cannot accept a friend request from yourself.');
        } else if ($user->is_friend($model)) {
            return Response::deny('You are already friends with this user.');
        } else if ($user->has_friend_request_from($model)) {
            return Response::allow();
        } else {
            return Response::deny('You do not have a friend request from this user.');
        }
    }

    public function decline_friend_request(User $user, User $model): Response
    {
        if ($user === null) {
            return Response::deny('You must be logged in to deny a friend request.');
        } else if ($user->id === $model->id) {
            return Response::deny('You cannot deny a friend request from yourself.');
        } else if ($user->is_friend($model)) {
            return Response::deny('You are already friends with this user.');
        } else if ($user->has_friend_request_from($model)) {
            return Response::allow();
        } else {
            return Response::deny('You do not have a friend request from this user.');
        }
    }

    public function remove_friend(User $user, User $model): Response
    {
        if ($user === null) {
            return Response::deny('You must be logged in to remove a friend.');
        } else if ($user->id === $model->id) {
            return Response::deny('You cannot remove yourself as a friend.');
        } else if ($user->is_friend($model)) {
            return Response::allow();
        } else {
            return Response::deny('You are not friends with this user.');
        }
    }

}
