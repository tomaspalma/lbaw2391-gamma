@if ((isset($checkUser) && $checkUser !== $notification->reaction->owner->username) || !isset($checkUser))
    <article class="notification-card w-1/2 mb-1 p-4 border shadow-md flex rounded-md md:flex-row sm:flex-col justify-center align-middle space-x-2 border-b-2 text-base {{$notification->read ? 'read-notification' : ''}}">
        <img class="rounded-full w-10 h-10" src="{{$notification->groupRequest->user->getProfileImage()}}" alt="{{$notification->groupRequest->user->username}}'s Profile Image">
        <p>
            <a href="users/{{$notification->groupRequest->user->username}}">
                {{$notification->groupRequest->user->username}}
            </a>
            asked to enter
            <a href="group/{{$notification->groupRequest->group->id}}">
                {{$notification->groupRequest->group->name}}
            </a>
            {{$date}}
        </p>

    </article>
@endif