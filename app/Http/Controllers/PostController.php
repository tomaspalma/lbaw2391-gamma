<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\Comment;

class PostController extends Controller
{

    public function showCreateForm() : View {

        // TODO set user loged in as 1 for now
        Auth::logout();
        Auth::loginUsingId(1);

        $groups = Auth::user()->groups;

        return view('pages.create_post', [
            'groups' => $groups
        ]);
    }

    public function create(Request $request) {
        
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

        return redirect('/post/'.$post->id);
    }

    public function showPost(string $id) : View {

        $post = Post::findOrFail($id);
        $comments = $post->comments()->get();
        
        // // verify if can see post
        // if($post->is_private && $post->owner()->isNot(Auth::user())) {
        //     // forbidenn. return to feed
        //     return redirect('/feed');
        // }

        return view('pages.post', [
            'post' => $post,
            'comments' => $comments
        ]);

    }

    public function showEditForm(string $id) : View {

        $post = Post::findOrFail($id);

        // TODO set user loged in as 1 for now
        Auth::logout();
        Auth::loginUsingId(1);

        if($post->owner()->is(Auth::user())) {
            // ok return edit form view

            $groups = Auth::user()->groups;

            return view('pages.edit_post', [
                'post' => $post,
                'groups' => $groups
            ]);
        }
        // forbiddem. return to feed
        return redirect('/feed');
    }

    public function update(Request $request, string $id) {

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file',
            'group' => 'nullable|integer',
            'is_private' => 'required|boolean'
        ]);

        $post = Post::findOrFail($id);

        // TODO set user loged in as 1 for now
        Auth::logout();
        Auth::loginUsingId(1);

        if($post->owner()->is(Auth::user())) {
            // ok return edit form view
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'attachment' => $request->attachment,
                'group_id' => $request->group,
                'is_private' => $request->is_private
            ]);

            return redirect('/post/'.$id);
        }

        // forbidden. return to feed
        return redirect('/feed');
    }

    public function delete(string $id) {

        $post = Post::findOrFail($id);

        // TODO set user loged in as 1 for now
        Auth::logout();
        Auth::loginUsingId(1);

        if($post->owner()->is(Auth::user())) {
            $post->delete();
            return redirect('/feed');
        }

        // forbidden. return to feed -
        return redirect('/feed');
    }
}
