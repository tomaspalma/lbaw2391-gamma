<article data-user-image="{{$user->getProfileImage()}}" data-username="{{$user->username}}" class="my-4 p-2 border-b flex md:flex-row sm:flex-col justify-between align-middle space-x-2">
    <div class="flex flex-row space-x-2 align-middle">
        <img class="rounded-full w-10 h-10" src="{{$user->getProfileImage()}}" alt="Profile Picture">
        <h1>
            <a href="{{'/users/' . $user->username}}" class="underline">
                {{$user->username}}
            </a>
        </h1>
    </div>
    @if($adminView)
    <div class="order-3 space-x-8">
        <button>
            <a target="_blank" href="{{'/users/' . $user->username . '/edit'}}">Edit</a>
        </button>
        <button class="block-reason-trigger" {{$user->is_app_banned() ? 'hidden' : ''}}>
            Block
        </button>
        <button class="unblock-confirmation-trigger" {{!$user->is_app_banned() ? 'hidden' : ''}}>
            Unblock
        </button>
        <button class="delete-confirmation-trigger">
            Delete
        </button>
    </div>
    @endif
</article>
