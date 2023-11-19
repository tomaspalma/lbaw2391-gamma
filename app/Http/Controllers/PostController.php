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

        $post->delete();
        return redirect('/feed');
    }
}
