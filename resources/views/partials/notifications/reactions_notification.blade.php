<article class="notification-card w-1/2 mb-1 p-4 border shadow-md flex rounded-md md:flex-row sm:flex-col justify-center align-middle space-x-2 border-b-2 text-base {{$notification->read ? 'unread-notification' : ''}}">
    <img class="rounded-full w-10 h-10" src="{{$notification->reaction->owner->getProfileImage()}}" alt="Profile Picture">
    <p>
        <a href="#">{{$notification->reaction->owner->username}}</a> {{$notification->verb()}} your post {{$notification->date}}
    </p>
</article>
@php
if(!$notification->read) {
    $notification->read = true;
    $notification->save();
}
@endphp
