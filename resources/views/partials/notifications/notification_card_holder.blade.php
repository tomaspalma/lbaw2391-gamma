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
    @endif

    @if(isset($notification->comment_id))
    @include('partials.notifications.comments_notification', [
    'notification' => $notification,
    'date' => $date
    ])
    @endif

    @if(!$notification->read)
    @php
    $notification->read = true;
    $notification->save();
    @endphp
    @endif

    @endforeach
</div>
