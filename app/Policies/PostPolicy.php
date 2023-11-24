<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Post $post): Response
    {
        // If post is private, only the owner and friends can see it (or an admin)
        if ($post->is_private) {
            if ($user === null) {
                return Response::deny('This post is private.');
            }
            return ($user->id === $post->author || $user->friends->contains($post->author) || $user->is_admin()) 
                ? Response::allow()
                : Response::deny('This post is private.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): Response
    {
        // Only authenticated users can create posts
        return $user !== null
            ? Response::allow()
            : Response::deny('You must be logged in to create a post.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): Response
    {
        // Only the owner can update a post
        return $user->id === $post->author
            ? Response::allow()
            : Response::deny('You must be the owner of this post to update it.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): Response
    {
        // Only the owner can delete a post (or an admin)
        return ($user->id === $post->author || $user->is_admin())
            ? Response::allow()
            : Response::deny('You must be the owner of this post to delete it.');
    }

    /**
     * Determine whether the user can have public posts.
     */
    public function publicPost(User $user): Response
    {
        return ($user->is_private) ? Response::deny('To create public posts you must change the visibility of your profile') : Response::allow();
    }

    public function add_reaction(User $user, Post $post): Response
    {
        $reaction = Reaction::where('author', '=', $user->id)->where('post_id', '=', $post->id)->get();
        return count($reaction) !== 1
            ? Response::allow()
            : Response::deny('You already have one reaction to this post.');
    }
    
    /**
      * Determines whether a user can remove a reaction from a post
    */
    public function remove_reaction(User $user): Response
    {
        $reaction = Reaction::where('author', '=', $user->id)->get();
        return count($reaction) === 1 
            ? Response::allow()
            : Response::deny('You do not have any reactions on this post.');
    }
}
