@extends('layouts.head')

<head>
    @vite(['resources/js/friends/requests.js', 'resources/js/friends/scroll.js'])
</head>

@include('partials.navbar')

<main class="center mx-4">
    <div class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <div class="flex w-1/2 border-t-4 p-2 justify-center {{ $tab === 'friends' ? 'border-t-4 border-black' : '' }}">
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
    <div>
        @foreach($friendRequests as $request)
        <div class="flex justify-between items-center bg-white p-4 m-2 rounded shadow" id="request-{{$request->sender->username}}">
            <div class="flex items-center space-x-4">
                <img src="{{ $request->sender->getProfileImage() }}" alt="{{ $request->sender->username }}'s Profile Image" class="w-12 h-12 rounded-full">
                <div>
                    <a href="{{'/users/' . $request->sender->username}}">
                        <h2 class="text-xl font-bold">{{ $request->sender->display_name }}</h2>
                        <p class="text-gray-500">{{ $request->sender->username }}</p>
                    </a>
                </div>
            </div>
            <form id="friendRequestForm" data-username="{{ $request->sender->username }}" class="flex items-center space-x-4 my-auto" method="post">
                @csrf
                <button type="submit" name="action" value="accept" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Accept</button>
                <button type="submit" name="action" value="decline" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Decline</button>
            </form>
        </div>
        @endforeach
    </div>
    <p id="noRequestsMessage" class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700 {{ count($friendRequests) ? 'hidden' : '' }} ">No friend requests found.</p>
    @endif
</main>

@include('partials.snackbar')
@include('partials.footer')
