<article data-user-image="{{$user->getProfileImage()}}" data-username="{{$user->username}}" class="my-4 p-2 border-b flex md:flex-row sm:flex-col justify-between items-center space-x-2 shadow rounded">
    <div class="flex flex-row space-x-2 items-center">
        <img class="rounded-full w-12 h-12" src="{{$user->getProfileImage()}}" alt="Profile Picture">
        <div class="flex flex-col">
            <a href="{{'/users/' . $user->username}}"> 
                <h2 class="text-xl font-bold no-underline">{{ $user->display_name }}</h2>
                <p class="text-gray-500 no-underline">{{ $user->username }}</p>
            </a>
        </div>
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

