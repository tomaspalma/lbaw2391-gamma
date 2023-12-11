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

}
