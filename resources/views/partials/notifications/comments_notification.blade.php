@if ((isset($checkUser) && $checkUser !== $notification->comment->owner->username) || !isset($checkUser))
<article class="notification-card w-1/2 mb-1 p-4 border shadow-md flex rounded-md md:flex-row sm:flex-col justify-center align-middle space-x-2 border-b-2 text-base {{$notification->read ? 'read-notification' : ''}}">
    <img class="rounded-full w-10 h-10" src="{{$notification->comment->owner->getProfileImage()}}" alt="Profile Picture">
    <p>
        <a href="{{route('profile', ['username' => $notification->comment->owner->username])}}">
            {{$notification->comment->owner->username}}
        </a>
        commented on your <a target="_blank" href="{{ route('post.show', ['id' => $notification->comment->post_id] ) }}">post</a>
        {{$date}}
    </p>
</article>
@endif
