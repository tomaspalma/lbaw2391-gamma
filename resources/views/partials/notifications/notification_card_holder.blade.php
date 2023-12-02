<div id="notification-cards" class="flex flex-col items-center w-full">
    @foreach ($notifications as $notification)
    @if(isset($notification->reaction_id))
    @php
    $date = App\Http\Controllers\DateController::format_date($notification->date);
    @endphp
    @include('partials.notifications.reactions_notification', [
    'notification' => $notification,
    'date' => $date
    ])
    @endif
    @endforeach
</div>
