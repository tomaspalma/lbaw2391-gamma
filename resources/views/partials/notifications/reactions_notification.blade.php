@if ((isset($checkUser) && $checkUser !== $notification->reaction->owner->username) || !isset($checkUser))
<article class="notification-card w-1/2 mb-1 p-4 border shadow-md flex rounded-md md:flex-row sm:flex-col justify-center align-middle space-x-2 border-b-2 text-base {{$notification->read ? 'read-notification' : ''}}">
    <img class="rounded-full w-10 h-10" src="{{$notification->reaction->owner->getProfileImage()}}" alt="Profile Picture">
    <p>
        <a href="#">{{$notification->reaction->owner->username}}</a> {{$notification->verb()}} your post {{$date}}
    </p>
</article>
@endif
@if(!$notification->read)
@php
$notification->read = true;
$notification->save();
@endphp
@endif
