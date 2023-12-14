<?php

namespace App\Policies;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PollPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function can_add_vote(User $user, PollOption $poll_option)
    {
        return $user->has_votes_on($poll_option)
            ? Response::deny("You already have a vote on this poll")
            : Response::allow();
    }
}
