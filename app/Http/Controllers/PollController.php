<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollOptionVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function addVote(Request $request, int $id)
    {

        $poll_option = PollOption::where('poll_id', $id)->where('name', $request->json('option'))->get();

        $this->authorize('can_add_vote', $poll_option);

        if (count($poll_option) === 0) {
            abort(422);
        } else {

            PollOptionVote::create([
                'user_id' => Auth::user()->id,
                'poll_option_id' => $poll_option[0]->id
            ]);
        }
    }
}
