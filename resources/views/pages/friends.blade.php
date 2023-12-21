@extends('layouts.head')

<head>
    @vite(['resources/js/friends/requests.js', 'resources/js/friends/scroll.js'])

    <title>{{ config('app.name', 'Laravel') }} | Friends</title>

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Friends",
    'url' => $url,
    'image' => $logo
    ])
</head>

@include('partials.navbar')

<main class="center mx-4">
    <div class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <div class="flex w-1/2 p-2 justify-center {{ $tab === 'friends' ? 'border-t-4 border-black' : '' }}">
            <a href="/users/{{$user->username}}/friends" class="hover:underline text-lg font-bold">Friends</a>
        </div>
        @if(auth()->user() && auth()->user()->id === $user->id)
        <div class="flex w-1/2 p-2 justify-center {{ $tab === 'requests' ? 'border-t-4 border-black' : '' }}">
            <a href="/users/{{$user->username}}/friends/requests" class="hover:underline text-lg font-bold" id="friend-requests-title">Pending ({{count($friendRequests)}})</a>
        </div>
        @endif
    </div>
    @if($tab=='friends')
    <div id="friends">
        @forelse($friends as $friend)
        @include('partials.user_card', ['user'=> $friend, 'adminView' => false])
        @empty
        <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No friends found.</p>
        @endforelse
    </div>
    @endif
    @if($tab=='requests')
    <div id="friend-requests">
        @foreach($friendRequests as $request)
        <div id="request-{{$request->sender->username}}">
        @include('partials.user_card', ['user' =>$request->sender, 'friendRequest' => true, 'adminView' => false])
        </div>
        @endforeach
    <p id="noRequestsMessage" class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700 {{ count($friendRequests) ? 'hidden' : '' }} ">No friend requests found.</p>
    </div>
    @endif
</main>

@include('partials.snackbar')
@include('partials.footer')
