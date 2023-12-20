@if ((isset($checkUser) && $checkUser !== $notification->reaction->owner->username) || !isset($checkUser))
<article class="notification-card mb-1 p-4 border shadow-md flex rounded-md md:flex-row sm:flex-col justify-center align-middle space-x-2 border-b-2 text-base {{$notification->read ? 'read-notification' : ''}}">
    <img class="rounded-full w-10 h-10" src="{{$notification->reaction->owner->getProfileImage('small')}}" alt="{{ $notification->reaction->owner->username }}'s Profile Image">
    <p>
        <a href="{{route('profile', ['username' => $notification->reaction->owner->username])}}">
            {{$notification->reaction->owner->username}}
        </a>
        {{$notification->verb()}} your
        {{isset($notification->reaction->post_id) ? 'post' : 'comment'}}
        {{$date}}
    </p>
</article>
@endif
