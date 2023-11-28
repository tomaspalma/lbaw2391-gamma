<?php

namespace App\Policies;

use App\Models\AppBan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
      * Determine whether or not the user has an app ban, e.g. if they have a ban to appeal from
      */
    public function can_appeal_appban(User $user): Response {
        $appban = AppBan::where('banned_user_id', $user->id)->get();

        if(count($appban) > 0 && $appban[0]->appeal === null) {
            return Response::allow();
        } else if(count($appban) <= 0) {
            return Response::deny("You do not have any app bans.");
        } else if($appban[0]->appeal !== null) {
            return Response::deny("You already appealed the ban and was not either accepted or rejected");
        }
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

}
