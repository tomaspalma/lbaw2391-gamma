<div id="notification-cards" class="flex flex-col items-center w-full">
    @foreach ($notifications as $notification)
        @if(isset($notification->reaction_id))
            @include('partials.notifications.reactions_notification', [
                'notification' => $notification
            ])
        @endif
    @endforeach
</div>
