<article data-user-image="{{ $user->getProfileImage() }}" data-username="{{ $user->username }}" class="m-2 p-4 border-b flex md:flex-row sm:flex-col justify-between items-center space-x-2 shadow rounded">
    <div class="flex flex-row justify-between items-center space-x-4">
        <img class="rounded-full w-12 h-12" src="{{ $user->getProfileImage() }}" alt="Profile Picture">
        <div>
            <a href="{{ '/users/' . $user->username }}" class="no-underline">
                <h2 class="display-name text-xl font-bold">{{ $user->display_name }}
                    @if(isset($group) && $user->is_owner($group))
                    <span class="group-status-text">Owner</span>
                    @endif
                </h2>
                <p class="text-gray-500">{{ $user->username }}</p>
            </a>
        </div>
    </div>

    <div class="order-3 space-x-8">
        @if($adminView)
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
        @endif

        @if(isset($is_group) && $is_group && Auth::user()->is_owner($group))
        @if(Auth::user()->is_owner($group) && !$user->is_owner($group))
        <div class="normal-user-actions">
            <button data-username="{{$user->username}}" data-group-id="{{$group->id}}" class="promote-group-member-confirmation-trigger-btn">
                Promote
            </button>
            <button data-username="{{$user->username}}" data-group-id="{{$group->id}}" class="ban-confirmation-trigger">
                Ban
            </button>
        </div>
        @endif

        @endif
    </div>

    @if(isset($appealView) && $appealView)
    <article data-username="{{ $user->username }}" class="hidden appban-appeal-reason">
        <h1 class="text-base font-bold text-center">Appeal</h1>
        <p>{{ $appeal->reason }}</p>
    </article>
    @endif
</article>
