<article 
    data-user-image="{{ $user->getProfileImage() }}" 
    data-username="{{ $user->username }}" 
    class="m-2 p-4 border-b flex md:flex-row sm:flex-col justify-between items-center space-x-2 shadow rounded"
>
    <div class="flex flex-row justify-between items-center space-x-4">
        <img class="rounded-full w-12 h-12" src="{{ $user->getProfileImage() }}" alt="Profile Picture">
        <div>
            <a href="{{ '/users/' . $user->username }}" class="no-underline">
                <h2 class="text-xl font-bold">{{ $user->display_name }}</h2>
                <p class="text-gray-500">{{ $user->username }}</p>
            </a>
        </div>
    </div>

    @if($adminView)
        <div class="flex space-x-8 items-center">
            @if(!isset($appealView) || !$appealView)
                <button>
                    <a target="_blank" href="{{ '/users/' . $user->username . '/edit' }}">Edit</a>
                </button>
            @endif
            <button class="block-reason-trigger" {{ $user->is_app_banned() ? 'hidden' : '' }}>
                Block
            </button>
            <button class="unblock-confirmation-trigger" {{ !$user->is_app_banned() ? 'hidden' : '' }}>
                Unblock
            </button>
            @if(!isset($appealView) || !$appealView)
                <button class="delete-confirmation-trigger">
                    Delete
                </button>
            @endif
            @if(isset($appealView) && $appealView)
                <button class="remove-confirmation-trigger">
                    Remove
                </button>
                <i class="appban-dropdown-arrow cursor-pointer fa-solid fa-angle-down"></i>
            @endif
        </div>
    @endif

    @if(isset($appealView) && $appealView)
        <article data-username="{{ $user->username }}" class="hidden appban-appeal-reason">
            <h1 class="text-base font-bold text-center">Appeal</h1>
            <p>{{ $appeal->reason }}</p>
        </article>
    @endif
</article>
