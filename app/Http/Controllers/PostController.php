<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;

class PostController extends Controller
{
    public function showCreateForm(): View
    {

        $this->authorize('create', Post::class);

        $groups = Auth::user()->groups;

        return view('pages.create_post', [
            'groups' => $groups
        ]);
    }

    public function create(Request $request) {

        $this->authorize('create', Post::class);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file',
            'group' => 'nullable|integer',
            'is_private' => 'required|boolean'
        ]);

        $last_id = DB::select('SELECT id FROM post ORDER BY id DESC LIMIT 1')[0]->id;
        $new_id = $last_id + 1;

        $post = Post::create([
            'id' => $new_id,
            'author' => Auth::user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'attachment' => $request->attachment,
            'group_id' => $request->group,
            'is_private' => $request->is_private
        ]);

        return redirect('/post/' . $post->id);
    }

    public function showPost(string $id)
    {

        // validate id
        if (!is_numeric($id)) {
            // not valid. return to feed
            return redirect('/feed');
        }

        $post = Post::findOrFail($id);

        $this->authorize('view', $post);

        $comments = $post->comments()->get();

        return view('pages.post', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function showEditForm(string $id)
    {

        // validate id
        if (!is_numeric($id)) {
            // not valid. return to feed
            return redirect('/feed');
        }

        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $groups = Auth::user()->groups;

        return view('pages.edit_post', [
            'post' => $post,
            'groups' => $groups
        ]);
    }

    public function update(Request $request, string $id)
    {

        // validate id
        if (!is_numeric($id)) {
            // not valid. return to feed
            return redirect('/feed');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file',
            'group' => 'nullable|integer',
            'is_private' => 'required|boolean'
        ]);

        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'attachment' => $request->attachment,
            'group_id' => $request->group,
            'is_private' => $request->is_private
        ]);

        return redirect('/post/'.$id);
    }

    public function delete(string $id)
    {

        // validate id
        if (!is_numeric($id)) {
            // not valid. return to feed
            return redirect('/feed');
        }

        $post = Post::findOrFail($id);

        $this->authorize('delete', $post);

        if($post->owner()->is(Auth::user())) {
            DB::transaction(function () use ($post) {
                $this->delete_post($post->id);
            });
        }
        return redirect('/feed');
    }

    /**
     * This should be used inside a transaction
     *
     * @$reaction_id The id of the user we want to delete
     * */
    private function delete_reaction($reaction_id)
    {
        DB::table('reaction_not')->where('reaction_id', $reaction_id)->delete();
        DB::table('reaction')->where('id', $reaction_id)->delete();
    }
    
    /**
     * This should be used inside a transaction
     * 
     * @$comment_id The id of the comment we want to delete
     */
    private function delete_comment($comment_id)
    {
        $comment_reactions = DB::table('reaction')->where('comment_id', $comment_id)->get();
        foreach ($comment_reactions as $reaction) {
            $this->delete_reaction($reaction->id);
        }
        DB::table('comment_not')->where('comment_id', $comment_id)->delete();
        DB::table('comment')->where('id', $comment_id)->delete();
    }

    /**
     * This should be used inside a transaction
     * 
     * @$post_id The id of the post we want to delete
     */

    private function delete_post($post_id)
    {
        $post_reactions = DB::table('reaction')->where('post_id', $post_id)->get();
        foreach ($post_reactions as $reaction) {
            $this->delete_reaction($reaction->id);
        }
        $post_comments = DB::table('comment')->where('post_id', $post_id)->get();
        foreach ($post_comments as $comment) {
            $this->delete_comment($comment->id);
        }
        DB::table('post_tag_not')->where('post_id', $post_id)->delete();
        DB::table('post_tag')->where('post_id', $post_id)->delete();
        DB::table('post')->where('id', $post_id)->delete();
    }
}


