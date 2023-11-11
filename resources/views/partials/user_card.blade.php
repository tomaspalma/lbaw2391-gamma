<article data-user-image="{{$user->image}}" data-username="{{$user->username}}" class="my-4 p-2 border-b flex justify-between align-middle space-x-2">
    <div class="flex flex-row space-x-2 align-middle">
        <img class="rounded-full w-10 h-10" src="{{$user->image}}" alt="Profile Picture">
        <h1>
            <a href="/user/${user.username}" class="underline">
                {{$user->username}}
            </a>
        </h1>
    </div>
    @if($adminView)
    <button class="delete-confirmation-trigger order-3">
        Delete
    </button>
    @endif
</article>
