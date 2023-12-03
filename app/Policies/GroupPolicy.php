<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{

    public function view(?User $user, Group $group): Response
    {
        if ($group->is_private) {
            if ($user === null) {
                return Response::deny('This group is private.');
            }

            $userIds = $group->users->pluck('id')->toArray();

            return (in_array($user->id, $userIds)|| $user->is_admin()) 
                ? Response::allow()
                : Response::deny('This group is private.');
        }

        return Response::allow();
    }

    public function alreadyIn(?user $user, Group $group): Response{
        $userIds = $group->users->pluck('id')->toArray();
        if(in_array($user->id, $userIds)){
            return Response::allow();
        }
        return Response::deny();
    }
}
