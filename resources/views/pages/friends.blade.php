@extends('layouts.head')

@include('partials.navbar')

<main class="center mx-4">
    <div class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <div class="flex w-1/2 border-t-4 border-black p-2 justify-center">
            <a href="/users/{{$user->username}}/friends" class="hover:underline text-lg font-bold">Friends</a>
        </div>

        <div class="flex w-1/2 p-2 justify-center">
            <a href="/users/{{$user->username}}/friends/requests" class="hover:underline text-lg font-bold">Pending</a>
        </div>
    </div>
    @if($tab=='friends')
        @forelse($friends as $friend)
            @include('partials.user_card', ['user'=> $friend, 'adminView' => false])
        @empty 
            <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No friends found.</p>
        @endforelse
    @endif
    @if($tab=='requests')
    @forelse($friendRequests as $request)
        <div class="flex justify-between items-center bg-white p-4 m-2 rounded shadow">
            <div class="flex items-center space-x-4">
                <img src="{{ $request->sender->getProfileImage() }}" alt="Profile Image" class="w-12 h-12 rounded-full">
                <div>
                    <h2 class="text-xl font-bold">{{ $request->sender->display_name }}</h2>
                    <p class="text-gray-500">{{ $request->sender->username }}</p>
                </div>
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Accept</button>
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Decline</button>
            </div>
        </div>
    @empty 
        <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No friend requests found.</p>
    @endforelse

    @endif
</main>