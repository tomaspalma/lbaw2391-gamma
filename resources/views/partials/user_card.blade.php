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
    <div class="order-3 space-x-8">

        <a class="block-reason-trigger" cursor:pointer" href="/users/{{$user->username}}/block" {{$user->is_app_banned() ? 'hidden' : ''}}>Block</a>
        <button class="unblock-confirmation-trigger" {{!$user->is_app_banned() ? 'hidden' : ''}}>
            Unblock </button>
        <button class="delete-confirmation-trigger">
            Delete
        </button>
    </div>
    @endif
</article>
