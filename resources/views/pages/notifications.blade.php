@extends('layouts.head')

@include('partials.navbar')

<h1 class="text-center text-2xl">
    Notifications
    @foreach ($notifications as $notification)
        @if(isset($notification->reaction_id))
            @include('partials.notifications.reactions_notification', ['notification' => $notification])
        @endif
    @endforeach
</h1>
