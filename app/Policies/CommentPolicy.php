<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    public function add_reaction(User $user, Comment $comment, string $reaction_type): Response
    {
        $reaction = Reaction::where('author', '=', $user->id)
            ->where('comment_id', '=', $comment->id)
            ->where('type', $reaction_type)->get();

        return count($reaction) !== 1
            ? Response::allow()
            : Response::deny('You already have one reaction to this comment.');
    }


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        // Only authenticated users can create comments
        return $user !== null
            ? Response::allow()
            : Response::deny('You must be logged in to create a comment.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): Response
    {
        // Only the owner can update a comment
        return $user->id === $comment->author
            ? Response::allow()
            : Response::deny('You must be the owner of this comment to update it.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): Response
    {
        // Only the owner can delete a comment (or an admin)
        return ($user->id === $comment->author || $user->is_admin())
            ? Response::allow()
            : Response::deny('You must be the owner of this comment to delete it.');
    }
}
