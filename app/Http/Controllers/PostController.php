<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Enums\ReactionType;
use App\Events\Reaction as EventsReaction;
use App\Models\Poll;
use App\Models\Group;
use App\Models\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\PostTagNot;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PostController extends Controller
{
    public function show_post_card(int $id, bool $preview)
    {
        $post = Post::find($id);

        return view('partials.post_card', ['post' => new PostResource($post), 'preview' => $preview]);
    }

    public function get_reactions(Request $request, int $id)
    {
        $post = Post::find($id);

        return response()->json(ReactionController::reactionsMap($post));
    }

    public function add_reaction(Request $request, int $id)
    {
        $request->validate([
            'type' => Rule::in(Reaction::$possible_types)
        ]);

        $post = Post::find($id);

        $reaction_type = $request->json('type');

        $this->authorize('add_reaction', [$post, $reaction_type]);

        Reaction::create([
            'author' => $request->user()->id,
            'post_id' => $id,
            'type' => $reaction_type
        ]);

        event(new EventsReaction($post->owner->username, $request->user(), $reaction_type, $id, null));
    }

    public function remove_reaction(Request $request, int $id)
    {
        $reaction = Reaction::where('author', $request->user()->id)
            ->where('post_id', $id)
            ->where('type', $request->json('type'))
            ->get()[0];

        if ($reaction !== null) {
            DB::transaction(function () use ($reaction) {
                DB::table('reaction_not')->where('reaction_id', $reaction->id)->delete();
                $reaction->delete();
            });
        }
    }

    public function showCreateForm(): View
    {
        $this->authorize('create', Post::class);

        $groupsOwner = Auth::user()->groups('owner')->get();
        $groupsNormal = Auth::user()->groups('normal')->get();

        $groups = $groupsOwner->merge($groupsNormal);


        return view('pages.create_post', [
            'groups' => $groups,
            'in_group_already' => false
        ]);
    }

    public function showCreateFormGroup(Request $request, string $id): View
    {
        $this->authorize('create', Post::class);

        $user = Auth::user();

        $groupsOwner = Auth::user()->groups('owner')->get();
        $groupsNormal = Auth::user()->groups('normal')->get();

        $groups = $groupsOwner->merge($groupsNormal);

        $groupp = Group::findOrFail($id);

        return view('pages.create_post', [
            'groups' => $groups,
            'in_group_already' => true,
            'groupp' => $groupp
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'group' => 'nullable|integer',
            'is_private' => 'required|boolean',
            'poll_options' => 'nullable|array'
        ]);

        if (!$request->is_private) {
            $this->authorize('publicPost', Post::class);
        }

        $last_id = DB::select('SELECT id FROM post ORDER BY id DESC LIMIT 1')[0]->id;
        $new_id = $last_id + 1;

        $pattern = '/\[\[(.*?)\]\]/';
        $parts = preg_split($pattern, $request->content, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        $tags = []; 
        foreach ($parts as $part) {
            if (!empty($part) && $part[0] === "{") {
                $json = json_decode($part, true);
                
                if(isset($json["username"])) {
                    $tags[] = $json["username"];
                }
            }
        }
        
        $post = Post::create([
            'id' => $new_id,
            'author' => Auth::user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'group_id' => $request->group,
            'is_private' => $request->is_private
        ]);

        foreach ($tags as $tag) {
            $user = User::where('username', $tag)->get();

            if (count($user) === 0) {
                continue;
            }

            $post_tag = PostTag::create([
                'user_id' => $user[0]->id,
                'post_id' => $post->id
            ]);

            PostTagNot::create([
                'post_tag_id' => $post_tag->id
            ]);
        }

        if ($request->hasFile('attachment')) {
            FileController::upload($request->file('attachment'), 'post', $post->id);
        }

        if (isset($request->poll_options) && $request->poll_options[0] !== null) {
            $poll = Poll::create([]);

            $post->poll_id = $poll->id;
            $post->save();

            foreach ($request->poll_options as $option) {
                PollOption::create([
                    'name' => $option,
                    'poll_id' => $poll->id
                ]);
            }
        }

        return redirect('/post/' . $post->id);
    }

    public function showPost(Request $request, string $id)
    {
        // validate id
        if (!is_numeric($id)) {
            // not valid. return to feed
            return redirect('/feed');
        }

        $post = Post::findOrFail($id);

        $this->authorize('view', $post);

        $comments = $post->comments()->orderBy('date', 'desc')->paginate(15);

        if ($request->is("api*")) {
            $commentCards = [];

            foreach ($comments as $comment) {
                $commentCards[] = view('partials.comment_card', ['comment' => $comment])->render();
            }

            return response()->json($commentCards);
        } else {
            $poll = $post->poll;
            if ($poll !== null) {
                $pollOptions = PollOption::with("votes")->where('poll_id', $poll->id)->get();
            }

            return view('pages.post', [
                'post' => $post,
                'comments' => $comments,
                'poll' => $poll === null ? null : $poll,
                'pollOptions' => isset($pollOptions) ? $pollOptions : null
            ]);
        }
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

        $groupsOwner = Auth::user()->groups('owner')->get();
        $groupsNormal = Auth::user()->groups('normal')->get();

        $groups = $groupsOwner->merge($groupsNormal);

        return view('pages.edit_post', [
            'post' => $post,
            'groups' => $groups,
            'attachment' => FileController::get('post', $post->id)
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
            'attachment' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
            'group' => 'nullable|integer',
            'is_private' => 'required|boolean'
        ]);

        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        if (!$request->is_private) {
            $this->authorize('publicPost', Post::class);
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'group_id' => $request->group,
            'is_private' => $request->is_private
        ]);

        if($request->remove_attachment === 1) {
            FileController::delete('post', $post->id);
            $post->update([
                'attachment' => null
            ]);
        }

        if ($request->hasFile('attachment')) {
            FileController::upload($request->file('attachment'), 'post', $post->id);
        }

        return redirect('/post/' . $id);
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

        DB::transaction(function () use ($post) {
            $this->delete_post($post->id);
        });
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

        FileController::delete('post', $post_id);
        
        DB::table('post_tag_not')->where('post_id', $post_id)->delete();
        DB::table('post_tag')->where('post_id', $post_id)->delete();
        DB::table('post')->where('id', $post_id)->delete();
    }
}
