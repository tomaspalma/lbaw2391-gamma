<article class="mt-4" id="poll">
    <h2 class="text-xl font-bold">Poll</h2>
    <div class="flex flex-col">
        @foreach ($pollOptions as $option)
            @php
                $isSelected = Auth::user() 
                    ? Auth::user()->has_votes_on_option(app\Models\PollOption::where('name', $option->name)->get()[0])
                    : false;
            @endphp
            <form id="{{$option->name}}" data-selected-vote="{{ $isSelected ? ($isSelected ? '1' : '0') : '0' }}" 
                data-option="{{$option->name}}" data-poll-id="{{$poll->id}}" 
                class="poll-option flex flex-col p-2 my-2 {{ $isSelected ? 'selected-poll-option' : 'unselected-poll-option' }} rounded-md hover:bg-black hover:text-white transition-colors" method="POST" action="{{route('poll.addVote', ['id' => $poll->id]) }}">
                <button type="submit" name="{{$option->name}}" class="flex flex-row justify-between">
                    <span>
                        {{ $option->name }}
                        @auth
                            <i class="poll-selected-checkmark text-green-500 fa-solid fa-check {{ Auth::user()->has_votes_on_option(app\Models\PollOption::where('name', $option->name)->get()[0]) ? '' : 'hidden' }}"></i>
                        @endauth
                    </span>
                    <span class="option-vote-counter">{{ count($option->votes) }}</span>
                </button>
            </form>
        @endforeach
    </div>
</article>
