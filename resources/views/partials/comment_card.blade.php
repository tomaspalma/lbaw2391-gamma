<article id="comment-{{$comment->id}}" data-entity="comment" data-entity-id="{{$comment->id}}" class="comment grid grid-cols-2 gap-y-4">
    <div class="flex flex-row flex-nowrap gap-x-4">
        <img src="{{ $comment->owner->getProfileImage('small') ?? 'hello' }}" class="rounded-full self-center w-8 h-8" alt="{{ $comment->owner->username }}'s Profile Image">
        <p class="text-gray-600">
                {{ $comment->owner->username }}
            @auth
                @if(Auth::user()->username === $comment->owner->username)
                    (<span class="italic">you</span>)
                @endif
            @endauth
        </p>
        <span class="text-gray-600">
            <time>{{ $comment->format_date() }} </time>
        </span>
    </div>
    <div class="dropdown flex gap-x-1 justify-self-end relative">
        @can('delete', $comment)
        <button class="dropdownButton text-black font-bold py-2 px-4 rounded">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <div class="dropdownContent absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="display:none;">
            @can('update', $comment)
            <a class="edit-comment-button block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 hover:no-underline">Edit</a>
            @endcan
            <a class="delete-comment-button block px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 hover:text-gray-900 hover:no-underline" comment-id="{{ $comment->id }}">Delete</a>
        </div>
        @endcan
    </div>
    <button class="save-comment hidden justify-self-end text-black font-bold py-2 px-4 rounded" comment-id="{{ $comment->id }}">Save</button>
    <p class="comment-content col-span-2 break-words">{{ $comment->content }}</p>
    <textarea class="edit-comment col-span-2 break-words hidden border border-black rounded-md p-2" name="content" rows="3"></textarea>
    @php
    $f = function($user, $comment) {
    return $user->comment_reaction($comment);
    }
    @endphp
    @include('partials.reactions', ['entity' => $comment, 'entity_function' => $f, 'entity_name' => 'comment'])

</article>
<hr class="my-2">
