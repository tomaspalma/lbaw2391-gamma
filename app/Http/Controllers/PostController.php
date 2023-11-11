<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;

class PostController extends Controller
{

    public function showCreateForm() : View {

        // TODO set user loged in as 1 for now
        Auth::logout();
        Auth::loginUsingId(1);

        return view('pages.create-post');
    }

    public function create(Request $request) {
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file',
            'group' => 'nullable|integer',
            'is_private' => 'required|boolean'
        ]);

        $post = Post::create([
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

        
        // // verify if can see post
        // if($post->is_private && $post->owner()->isNot(Auth::user())) {
        //     // forbidenn. return to feed
        //     return redirect('/feed');
        // }

        return view('pages.post', [
            'post' => $post
        ]);

    }

    public function showEditFrom(string $id) : View {

        $post = Post::findOrFail($id);

        // TODO set user loged in as 1 for now
        Auth::logout();
        Auth::loginUsingId(1);

        if($post->owner()->is(Auth::user())) {
            // ok return edit form view
            return view('pages.edit-post', [
                'post' => $post
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
}
