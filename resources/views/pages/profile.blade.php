@extends('layouts.head')
<head>
    @vite(['resources/css/app.css', 'resources/js/components/dropdown_dots.js', 'resources/js/profile/delete.js', 'resources/js/profile/friends.js'])
</head>

@include('partials.navbar')

@include('partials.confirm_modal')

<div class="max-w-screen-md mx-auto pb-4">
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6 border border-black">
        <div class="flex justify-between items-center">
            <button class="text-black font-bold py-2 px-4 rounded opacity-0 cursor-default">
                <i class="fas fa-ellipsis-v"></i>                     
            </button>
            <div class="flex-grow text-center">
                <h2 class="text-2xl font-bold text-gray-700">Profile</h2>
            </div>
            @can('update', $user)
            <div class="relative inline-block text-left">
                <button id="dropdownButton" class="text-black font-bold py-2 px-4 rounded">
                    <i class="fas fa-ellipsis-v"></i>                     
                </button>
                <div id="dropdownContent" class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="display:none;">
                    <a href="{{ route('edit_profile',['username' => $user->username]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">Edit</a>
                    <a id="deleteLink" class="block px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-100 hover:text-gray-900">Delete</a>
                </div>
                <input type="hidden" id="auth-user" value="{{ auth()->user()->username }}">
            </div>     
            @endcan
        </div>        
        <div class="mt-6 flex flex-col md:flex-row -mx-3">
            <div class="md:flex-1 px-3">
                <div class="mb-4">
                    <img src="{{ $user->getProfileImage() }}" alt="User Profile"
                        class="rounded-full w-32 h-32 md:w-48 md:h-48 mx-auto object-cover">
                </div>
                <div class="flex items-end mb-4 justify-center">
                    <label class="text-xl font-bold text-gray-700">{{$user->display_name}}</label>
                    <span class="ml-2 text-xs text-gray-600 pb-1">
                        {{$user->is_private ? 'Private' : 'Public'}}
                    </span>
                </div>
                @if(auth()->user() && auth()->user()->id != $user->id && !auth()->user()->is_friend($user) && !auth()->user()->has_sent_pending_friend_request($user))
                    <form action="{{ route('add_friend_request', ['username' => $user->username]) }}" id="friendForm" method="post" data-method="post">
                        @csrf
                        <button type="submit" class="text-white bg-gray-800 font-bold py-2 px-4 rounded">
                            Add Friend
                        </button>
                    </form>
                @else
                    @if(auth()->user() && auth()->user()->id != $user->id && auth()->user()->has_sent_pending_friend_request($user)) 
                        <form action="{{ route('remove_friend_request', ['username' => $user->username]) }}" id="friendForm" method="post" data-method="delete">
                            @csrf
                            <button type="submit" class="text-white bg-gray-800 font-bold py-2 px-4 rounded">
                                Cancel Friend Request
                            </button>
                        </form>
                    @else 
                        @if(auth()->user() && auth()->user()->id != $user->id && auth()->user()->is_friend($user))
                            <form action="{{ route('remove_friend', ['username' => $user->username]) }}" id="friendForm" method="post" data-method="delete">
                                @csrf
                                <button type="submit" class="text-white bg-gray-800 font-bold py-2 px-4 rounded">
                                    Remove Friend
                                </button>
                            </form>
                        @endif
                    @endif
                @endif

                <div class="mb-4">
                    <label class="text-sm text-gray-600">Username</label>
                    <div class="font-semibold text-gray-800" id="username">{{$user->username}}</div>
                </div>
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Email</label>
                    <div class="font-semibold text-gray-800">{{$user->email}}</div>
                </div>
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Academic Status</label>
                    <div class="font-semibold text-gray-800">{{$user->academic_status}}</div>
                </div>
            </div>
        </div>
    </div>
    @forelse($posts as $post)
        @can('view', $post)
            @include('partials.post_card', ['post'=> $post, 'preview' => false])
        @endcan
    @empty <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No friends found.</p>
    @endforelse
</div>
