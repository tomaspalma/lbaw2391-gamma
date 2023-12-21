<article data-user-image="{{ $user->getProfileImage('small') }}" data-username="{{ $user->username }}" class="m-2 p-4 border-b flex flex-col justify-between align-middle space-x-2 shadow rounded">
    <div class="flex flex-col md:flex-row justify-between">
        <div class="flex flex-row md:justify-between items-center space-x-4">
            <img class="rounded-full w-12 h-12" src="{{ $user->getProfileImage('small') }}" alt="Profile Picture">
            <div>
                <a href="{{ '/users/' . $user->username }}" class="no-underline">
                    <h2 class="text-xl font-bold display-name">{{ $user->display_name }}
                        @if(isset($group->id) && $user->is_owner($group->id))
                        <span class="group-status-text">Owner</span>
                        @endif
                    </h2>
                    <p class="text-gray-500">{{ $user->username }}</p>
                </a>
            </div>
        </div>

        @if($adminView)
            <div class="flex space-x-8 mt-4 md:mt-0 justify-center items-center">
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
            <button class="appban-dropdown-arrow">
                <i class="fa-solid fa-angle-down arrow"></i>
            </button>
            @endif
            </div>


            @endif

            @if(!isset($received_invite) && !isset($pending_invite) && isset($invite) && $invite)
            <form data-group-id="{{$group->id}}" data-username="{{$user->username}}" class="invite-form" action="{{ route('group.inviteuser', ['id' => $group->id, 'username' => $user->username]) }}" method="post">
                @csrf
                @method('POST')
                <input class="hidden" value="{{$user->id}}" name="user_id">
                <button type="submit" data-username="{{$user->username}}" data-group-id="{{$group->id}}" class="promote-group-member-confirmation-trigger-btn">
                    Invite User
                </button>
            </form>
            @endif

            @if(isset($pending_invite) && $pending_invite)
                <div class="flex flex-col items-center space-y-2">
                    <p>Pending</p>
                    <i class="text-blue-400 text-2xl fa-solid fa-circle-info"></i>
                </div>
            @endif

            @if(isset($received_invite) && $received_invite)
            <div>
                <div class="flex flex-row space-x-2">
                    <form data-group-id="{{$received_invite->group->id}}" data-username="{{$received_invite->user->username}}" class="accept-invite-form">
                        <button type="submit" class="form-button-blue rounded-md p-2">
                            Accept
                        </button>
                    </form>
                    <form data-group-id="{{$received_invite->group->id}}" data-username="{{$received_invite->user->username}}" class="reject-invite-form">
                        <button type="submit" class="form-button-red rounded-md p-2">
                            Reject
                        </button>
                    </form>
                </div>
                <p class="success-invite-text hidden text-green-500">You accepted the group invite.</p>
            </div>
            @endif

            @if(isset($is_group) && $is_group && Auth::user() != null && Auth::user()->is_owner($group->id))
            @if(Auth::user()->is_owner($group->id) && !$user->is_owner($group->id))
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

            @if(isset($friendRequest) && $friendRequest) 
            <form data-username="{{ $user->username }}" class="friendRequestForm flex items-center space-x-4 my-auto" method="post">
                @csrf
                <button type="submit" name="action" value="accept" class="form-button-blue font-bold py-2 px-4 rounded">Accept</button>
                <button type="submit" name="action" value="decline" class="form-button-red font-bold py-2 px-4 rounded">Decline</button>
            </form>
            @endif
    </div>

    @if(isset($appealView) && $appealView)
    <article data-username="{{ $user->username }}" class="hidden appban-appeal-reason">
        <h1 class="text-base font-bold text-center">Appeal</h1>
        <p>{{ $appeal->reason }}</p>
    </article>
    @endif

</article>

