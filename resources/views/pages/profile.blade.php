@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/components/dropdown_dots.js', 'resources/js/profile/delete.js', 'resources/js/profile/scroll.js', 'resources/js/profile/friends.js'])

    <title>{{ config('app.name', 'Laravel') }} | {{$user->username}} profile</title>
</head>

@include('partials.navbar')

@include('partials.confirm_modal')

<div class="max-w-screen-md mx-auto pb-4">
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6 border border-black flex flex-col">
        <div class="flex items-center justify-end">
            @can('update', $user)
            <div class="relative inline-block text-left justify-self-end">
                <button id="" class="dropdownButton text-black font-bold py-2 px-4 rounded">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div id="" class="dropdownContent absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="display:none;">
                    <a href="{{ route('edit_profile',['username' => $user->username]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Edit</a>
                    <a id="deleteLink" class="block px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 hover:text-gray-900">Delete</a>
                </div>
                <input type="hidden" id="auth-user" value="{{ auth()->user()->username }}">
            </div>
            @endcan
        </div>
        <div class="flex flex-col md:flex-row -mx-3 items-center text-center md:text-start">
            <div class="md:flex-1 px-3">
                <div class="mb-4">
                    <img src="{{ $user->getProfileImage() }}" alt="User Profile" class="rounded-full w-24 h-24 md:w-40 md:h-40 mx-auto object-cover">
                </div>
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-700">{{ $user->display_name }}</h2>
                    <p id="username" class="text-gray-500">{{ $user->username }}</p>
                </div>
            </div>
            <div class="md:flex-1 px-3 mt-4 md:mt-0 w-full md:w-1/2 md:mr-4">
                @if($user->description)
                    <p class="mb-4 text-gray-700 w-full break-words">{{ $user->description }}</p>
                @endif
                @can('view_information', $user)
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Academic Status</label>
                    <p class="font-semibold text-gray-800">{{$user->academic_status}}</p>
                </div>
                @if($user->university)
                <div class="mb-4">
                    <label class="text-sm text-gray-600">University</label>
                    <p class="font-semibold text-gray-800">{{$user->university}}</p>
                </div>
                @endif
                @endcan
                @if($user->id == auth()->user()->id)
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Email</label>
                    <p class="font-semibold text-gray-800">{{$user->email}}</p>
                </div>
                @endif
                <div class="flex flex-col space-y-2">
                    @can('view_friends', $user)
                    <a href="{{ route('friends_page', ['username' => $user->username]) }}" class="text-l font-bold text-gray-700" id="friends-link">
                        {{$user->friends()->count()}} friend{{$user->friends()->count() == 1 ? '' : 's'}}
                    </a>
                    @endcan
                    @can('send_friend_request', $user)
                    <form class="m-0" action="{{ route('send_friend_request', ['username' => $user->username]) }}" id="friendForm" method="post" data-method="post">
                        @csrf
                        <button type="submit" class="text-white bg-gray-800 font-bold py-2 px-4 rounded">
                            Send Friend Request
                        </button>
                    </form>
                    @endcan
                    @can('cancel_friend_request', $user)
                    <form class="m-0" action="{{ route('cancel_friend_request', ['username' => $user->username]) }}" id="friendForm" method="post" data-method="delete">
                        @csrf
                        <button type="submit" class="text-white bg-gray-800 font-bold py-2 px-4 rounded">
                            Cancel Friend Request
                        </button>
                    </form>
                    @endcan
                    @can('remove_friend', $user)
                    <form class="m-0" action="{{ route('remove_friend', ['username' => $user->username]) }}" id="friendForm" method="post" data-method="delete">
                        @csrf
                        <button type="submit" class="text-white bg-gray-800 font-bold py-2 px-4 rounded">
                            Remove Friend
                        </button>
                    </form>
                    @endcan
                    @can('accept_friend_request', $user)
                    <button type="button" onclick="window.location.href='{{ route('friend_requests_page', ['username' => auth()->user()->username]) }}'" class="text-white bg-gray-800 font-bold py-2 px-4 rounded">
                        See Pending Friend Request
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <div id="posts">
        @forelse($posts as $post)
        @can('view', $post)
        @include('partials.post_card', ['post'=> $post, 'preview' => false])
        @endcan
        @empty <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No posts found.</p>
        @endforelse
    </div>
</div>

@include('partials.snackbar')
