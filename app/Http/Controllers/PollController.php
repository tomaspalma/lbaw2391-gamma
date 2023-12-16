<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollOptionVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    public function addVote(Request $request, int $id)
    {
        $poll_option = PollOption::where('poll_id', $id)->where('name', $request->json('option'))->get();

        if (count($poll_option) === 0) {
            abort(422);
        } else {
            $poll = $poll_option[0]->poll;

            $this->authorize('add_option', $poll_option[0]);
            
            DB::transaction(function () use ($request, $poll, $poll_option) {
                PollOptionVote::where('user_id', $request->user()->id)
                    ->where('poll_id', $poll->id)->delete();

                PollOptionVote::create([
                    'user_id' => Auth::user()->id,
                    'poll_option_id' => $poll_option[0]->id,
                    'poll_id' => $poll->id
                ]);
            });
        }
    }

    public function removeVote(Request $request, int $id)
    {
        $poll_option = PollOption::where('poll_id', $id)->where('name', $request->json('option'))->get();

        if (count($poll_option) === 0) {
            abort(422);
        } else {
            $this->authorize('remove_option', $poll_option[0]);

            PollOptionVote::where('user_id', $request->user()->id)
                ->where('poll_option_id', $poll_option[0]->id)->delete();
        }
    }
}
