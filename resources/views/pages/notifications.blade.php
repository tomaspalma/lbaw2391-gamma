@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/notifications/filter.js'])

    <title>{{ config('app.name', 'Laravel') }} | Notifications</title>

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Notifications",
    'url' => $url,
    'image' => $logo
    ])

</head>

@include('partials.navbar')

<h1 class="text-center text-2xl">Notifications</h1>
<div class="flex flex-col items-center md:mb-12">
    <label for="notification-types">Filter</label>
    <select id="notification-types" class="w-1/8 bg-gray-50 border mb-4 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option value="all" selected>All</option>
        <option value="reactions">Reactions</option>
        <option value="comments">Comments</option>
        <option value="friend-requests">Friend Requests</option>
        <option value="group-requests">Group Requests</option>
        <option value="group-invites">Group Invites</option>
    </select>
    @include('partials.notifications.notification_card_holder', ['notification' => $notifications])
</div>

@include('partials.footer')
