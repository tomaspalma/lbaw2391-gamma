<div id="notification-cards" class="flex flex-col items-center w-full">
    @foreach ($notifications as $notification)
    @php
    $date = App\Http\Controllers\DateController::format_date($notification->date);
    @endphp
    @if(isset($notification->reaction_id))
        @include('partials.notifications.reactions_notification', [
        'notification' => $notification,
        'date' => $date
        ])
    @elseif(isset($notification->friend_request))
        @php
        $user = $notification->is_accepted === null ? $notification->sender : $notification->receiver;
        @endphp
        @if($user->username !== auth()->user()->username)
            @include('partials.notifications.friend_request_notification', [
            'user' => $user,
            'notification' => $notification,
            'date' => $date
            ])
        @endif
    @endif
    @endforeach
</div>
