@extends('layouts.head')

@include('partials.navbar')

<main class="center mx-4">
    <ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <li class="flex w-1/2 border-t-4 border-black p-2 justify-center">
            <a href="/users/{{$user->username}}/friends" class="hover:underline">Friends</a>
        </li>

        <li class="flex w-1/2 p-2 justify-center">
            <a href="/users/{{$user->username}}/friends/requests" class="hover:underline">Pending</a>
        </li>
    </ul>
    @if($tab=='friends')
    @forelse($friends as $friend)
        @include('partials.user_card', ['user'=> $friend, 'adminView' => false])
    @empty <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No posts found.</p>
    @endforelse
    @endif
    @if($tab=='requests')
    @forelse($friendRequests as $request)
        <div>
            <h2>{{ $request->user->username }}</h2>
            <button type="submit">Accept</button>
            <button type="submit">Decline</button>
        </div>
    @empty <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No friend requests found.</p>
    @endforelse
    @endif
</main>
