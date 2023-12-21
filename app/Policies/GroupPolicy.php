<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\GroupInvite;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    public function can_modify(User $user, Group $group)
    {
        return $user->is_owner($group->id)
            ? Response::allow()
            : Response::deny("You are not an owner of this group.");
    }

    public function invite(User $user, Group $group, User $user_model)
    {
        $invites = GroupInvite::where('user_id', $user_model->id)
            ->where('group_id', $group->id)
            ->where('is_accepted', false)
            ->get();

        return $user->is_owner($group->id) && count($invites) === 0
            ? Response::allow()
            : Response::deny("You cannot invite this user.");
    }

    public function viewPostsAndMembers(?User $user, Group $group): Response
    {
        if ($group->is_private) {
            if ($user === null) {
                return Response::deny('This group is private.');
            }

            $userIds = $group->users->pluck('id')->toArray();
            $ownersIds = $group->owners->pluck('id')->toArray();

            return (in_array($user->id, $userIds) || in_array($user->id, $ownersIds) || $user->is_admin())
                ? Response::allow()
                : Response::deny('This group is private.');
        }

        return Response::allow();
    }

    public function alreadyIn(?user $user, Group $group): Response
    {

        if ($user === null) {
            return Response::deny();
        }
        $userIds = $group->users->pluck('id')->toArray();
        $ownerIds = $group->group_owners()->pluck('users.id')->toArray();
        if (in_array($user->id, $userIds) || in_array($user->id, $ownerIds)) {
            return Response::allow();
        }
        return Response::deny();
    }

    public function PendingOption(?user $user, Group $group): Response
    {

        if ($user->is_pending($group->id) && $group->is_private) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function create(?User $user): Response
    {
        return $user !== null
            ? Response::allow()
            : Response::deny('You must be logged in to create a group.');
    }
}
