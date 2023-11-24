<div id="reaction-popup" class="flex flex-row post-action-bar space-x-4">
    <button data-entity-id="{{$entity->id}}" data-reaction-type="LIKE" class="reaction text-blue-700 rounded-lg hover:bg-blue-400 hover:text-white p-2">
        <i class="fa-regular fa-thumbs-up"></i>
        Like
    </button>
    <button data-entity-id="{{$entity->id}}"data-reaction-type="DISLIKE" class="reaction text-red-500 rounded-lg hover:bg-red-400 hover:text-white p-2">
        <i class="fa-regular fa-thumbs-down"></i>
        Dislike
    </button>
    <div id="toggle-reaction-popup" class="relative flex items-center justify-center">
        <div id="other-reactions-popup-menu" class="hidden absolute bottom-6 mb-2 reaction-bar rounded-lg border-2 bg-white text-black w-full">
            <ul class="flex flex-row space-x-2 text-white text-l">
                <li data-entity-id="{{$entity->id}}"  data-reaction-type="HEART" class="reaction text-purple-700 hover:bg-purple-400 hover:text-white p-2">
                    <i class="fa-regular fa-heart"></i>
                </li>
                <li data-entity-id="{{$entity->id}}" data-reaction-type="STAR" class="reaction text-yellow-700 hover:bg-yellow-400 hover:text-white p-2">
                    <i class="fa-regular fa-star"></i>
                </li>
            </ul>
        </div>
        <button class="rounded-lg hover:border-black p-2 hover:bg-gray-300 hover:text-white">
            <i class="fa-regular fa-plus"></i>
            More
        </button>
    </div>
</div>
