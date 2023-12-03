<article id="comment-{{$comment->id}}" data-entity="comment" data-entity-id="{{$comment->id}}" class="comment">
    <div class="flex max-w-full overflow-auto space-x-4">
        <img src="{{ $comment->owner->getProfileImage() ?? 'hello' }}" class="rounded-full self-center w-8 h-8">
        <div class="grow">
            <p class="text-gray-600">{{ $comment->owner->username }}</p>
            <p class="">{{ $comment->content }}</p>
        </div>
        @can('delete', $comment)
        <button type="button" class="delete-comment-button bg-red-500 text-white self-center py-1 px-2 rounded-md" comment-id="{{ $comment->id }}">Delete</button>
        @endcan
    </div>
    @php
    $f = function($user, $comment) {
    return $user->comment_reaction($comment);
    }
    @endphp
    @include('partials.reactions', ['entity' => $comment, 'entity_function' => $f, 'entity_name' => 'comment'])
    <hr class="my-2">
</article>
