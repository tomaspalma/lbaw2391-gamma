@php
    $count = count(Auth::user()->normal_notifications()->where('read', false))-count(Auth::user()->friend_request_notifications()->where('is_accepted', null));
@endphp
<a href="/notifications" class="block py-2 pl-3 pr-4">
    <div class="relative flex flex-row md:flex-col space-x-1 md:space-x-0">
        <i class="hidden md:inline fa-solid fa-bell text-2xl"></i>
        <span class="md:hidden">Notifications</span>
        <span id="notification-counter" class="{{$count > 0 ? '' : 'hidden'}} text-xs md:absolute md:bottom-3 md:left-1.5 bg-red-500 text-white w-5 h-5 flex items-center justify-center rounded-full">
            {{max($count, 0)}}
        </span>
    </div>
</a>
