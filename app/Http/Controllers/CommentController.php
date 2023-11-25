<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use App\Models\Comment;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
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

        return new CommentResource($comment);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $comment = Comment::findOrFail($request->id);

        $this->authorize('delete', $comment);

        if ($comment == null) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }


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
