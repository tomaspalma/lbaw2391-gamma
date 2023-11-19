<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
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
        return ($user->id === $model->id || $user->is_admi()) ? Response::allow() : Response::deny('You do not own this profile.');
    }

}
