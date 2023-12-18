@auth
<div class="flex flex-row post-action-bar space-x-4 mb-2">
    <button data-entity="{{$entity_name}}" data-entity-id="{{$entity->id}}" data-reaction-type="LIKE" class="{{isset($entity_function(Auth::user(), $entity)['LIKE']) ? 'highlighted like-highlighted' : 'like-nonhighlighted'}} reaction rounded-lg hover:bg-blue-400 hover:text-white p-2">
        <i class="fa-regular fa-thumbs-up"></i>
        Like
    </button>
    <button data-entity="{{$entity_name}}" data-entity-id="{{$entity->id}}" data-reaction-type="DISLIKE" class="{{isset($entity_function(Auth::user(), $entity)['DISLIKE']) ? 'highlighted dislike-highlighted' : 'dislike-nonhighlighted'}} reaction rounded-lg hover:bg-red-400 hover:text-white p-2">
        <i class="fa-regular fa-thumbs-down"></i>
        Dislike
    </button>
    <div class="toggle-reaction-popup relative flex items-center justify-center">
        <div class="other-reactions-popup-menu hidden absolute bottom-6 mb-2 reaction-bar rounded-lg border-2 bg-white text-black">
            <ul class="flex flex-row space-x-2 text-white text-l">
                <li data-entity="{{$entity_name}}" data-entity-id="{{$entity->id}}" data-reaction-type="HEART" class="{{isset($entity_function(Auth::user(), $entity)['HEART']) ? 'highlighted heart-highlighted' : 'heart-nonhighlighted'}} reaction hover:bg-purple-400 hover:text-white p-2">
                    <i class="fa-regular fa-heart"></i>
                </li>
                <li data-entity="{{$entity_name}}" data-entity-id="{{$entity->id}}" data-reaction-type="STAR" class="{{isset($entity_function(Auth::user(), $entity)['STAR']) ? 'highlighted star-highlighted' : 'star-nonhighlighted'}} reaction hover:bg-yellow-400 hover:text-white p-2">
                    <i class="fa-regular fa-star"></i>
                </li>
                <li data-entity="{{$entity_name}}" data-entity-id="{{$entity->id}}" data-reaction-type="HANDSHAKE" class="{{isset($entity_function(Auth::user(), $entity)['HANDSHAKE']) ? 'highlighted star-highlighted' : 'handshake-nonhighlighted'}} reaction hover:bg-yellow-400 hover:text-white p-2">
                    <i class="fa-regular fa-handshake"></i>
                </li>
                <li data-entity="{{$entity_name}}" data-entity-id="{{$entity->id}}" data-reaction-type="HANDPOINTUP" class="{{isset($entity_function(Auth::user(), $entity)['HANDPOINTUP']) ? 'highlighted star-highlighted' : 'handpointup-nonhighlighted'}} reaction hover:bg-yellow-400 hover:text-white p-2">
                    <i class="fa-regular fa-hand-point-up"></i>
                </li>
            </ul>
        </div>
        <button class="rounded-lg hover:border-black p-2 hover:bg-gray-300 hover:text-white">
            <i class="fa-regular fa-plus"></i>
            More
        </button>
    </div>
</div>
@endauth
<div class="reactions-list flex flex-row space-x-2 items-center">
    @php
    $reactions = App\Http\Controllers\ReactionController::reactionsMap($entity);
    @endphp
    @foreach ($reactions as $icon => $metadata)
    <div data-reaction-entity-id="" class="{{$post->id}}-{{$metadata[2]}}">
        <i class="fa-solid {{$icon}} {{$metadata[1]}}"></i>
        <span class="reaction-count">{{$metadata[0]}}</span>
    </div>
    @endforeach
</div>
