<?php

namespace App\Policies;

use App\Models\PollOption;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PollOptionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function add_option(User $user, PollOption $poll_option) {
        return $user->has_votes_on_option($poll_option)
            ? Response::deny("You already voted on this option!")
            : Response::allow();
    }

    public function remove_option(User $user, PollOption $poll_option) {
        return $user->has_votes_on_option($poll_option)
            ? Response::allow()
            : Response::deny("You don't have any votes on this option!");
    }
}
