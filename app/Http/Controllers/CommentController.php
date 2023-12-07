<?php

namespace App\Http\Controllers;

use App\Events\Reaction as EventsReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use App\Models\Comment;
use App\Http\Resources\CommentResource;
use App\Models\Reaction;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function get_reactions(int $id)
    {
        $comment = Comment::find($id);

        return response()->json(ReactionController::reactionsMap($comment));
    }

    public function remove_reaction(Request $request, int $id)
    {
        $request->validate([
            'type' => Rule::in(Reaction::$possible_types)
        ]);


        $this->authorize('remove_reation', [$comment, $reaction_type]);

        $reaction = Reaction::where('author', $request->user()->id)
            ->where('comment_id', $id)
            ->where('type', $request->json('type'))
            ->get()[0];

        if ($reaction !== null) {
            DB::transaction(function () use ($reaction) {
                DB::table('reaction_not')->where('reaction_id', $reaction->id)->delete();
                $reaction->delete();
            });
        }
    }

    /**
     * Adds a reaction to a comment
     */
    public function add_reaction(Request $request, int $id)
    {
        $request->validate([
            'type' => Rule::in(Reaction::$possible_types)
        ]);

        $comment = Comment::find($id);

        $reaction_type = $request->json('type');

        $this->authorize('add_reaction', [$comment, $reaction_type]);

        Reaction::create([
            'author' => $request->user()->id,
            'comment_id' => $id,
            'type' => $reaction_type
        ]);

        event(new EventsReaction($comment->owner->username, $request->user(), $reaction_type, null, $id));
    }

    public function create(Request $request)
    {

        $this->authorize('create', Comment::class);

        $request->validate([
            'post_id' => 'required|integer',
            'content' => 'required|string'
        ]);

        $last_id = DB::select('SELECT id FROM comment ORDER BY id DESC LIMIT 1')[0]->id;
        $new_id = $last_id + 1;

        $comment = Comment::create([
            'id' => $new_id,
            'post_id' => $request->post_id,
            'author' => Auth::user()->id,
            'content' => $request->content
        ]);

        return response()->json(view('partials.comment_card', ['comment' => $comment])->render());
    }

    public function showEditForm(int $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('update', $comment);

        return view('pages.edit_comment', [
            'comment' => $comment
        ]);
    }

    public function update(Request $request, int $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string'
        ]);

        $comment->content = $request->content;
        $comment->save();

        return new CommentResource($comment);
    }

    public function delete(int $id)
    {

        $comment = Comment::findOrFail($id);

        $this->authorize('delete', $comment);

        DB::transaction(function () use ($comment) {
            $this->delete_comment($comment->id);
        });

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted'
        ], 200);
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
}
