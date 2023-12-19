@if ((isset($checkUser) && $checkUser !== $notification->user) || !isset($checkUser))
<article class="notification-card w-1/2 mb-1 p-4 border shadow-md flex rounded-md md:flex-row sm:flex-col justify-center align-middle space-x-2 border-b-2 text-base {{$notification->read ? 'read-notification' : ''}}">
    <img class="rounded-full w-10 h-10" src="{{$notification->user}}" alt="{{ $notification->user}}'s Profile Image">
    <p>
        <a href="{{route('profile', ['username' => $notification->user])}}">
            {{$notification->user}}
        </a>
        {{$notification->verb()}} your
        {{isset($notification->reaction->post_id) ? 'post' : 'comment'}}
        {{$date}}
    </p>
</article>
@endif
