<article class="notification-card w-1/2 mb-1 p-4 border shadow-md flex rounded-md md:flex-row sm:flex-col justify-center align-middle space-x-2 border-b-2 text-base {{$notification->read ? 'read-notification' : ''}}">
    <img class="rounded-full w-10 h-10" src="{{$notification->groupInvite->owner->getProfileImage()}}" alt="{{$notification->groupInvite->owner->username}}'s Profile Image">
    <p>
        <a href="users/{{$notification->groupInvite->owner->username}}">
            {{$notification->groupInvite->owner->username}}</a>
        invited you to enter
        <a href="group/{{$notification->groupInvite->group->id}}">
            {{$notification->groupInvite->group->name}}
        </a>
        {{$date}}
    </p>
</article>