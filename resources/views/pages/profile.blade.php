@extends('layouts.head')

<head>
    @vite('resources/css/app.css')
</head>

@include('partials.navbar')

<div class="max-w-screen-md mx-auto p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6 border border-black">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold text-gray-700 text-center order-2">User Profile</h2>
            <button class="text-black px-4 py-2 rounded order-3">
                @auth
                    @if(auth()->user()->id === $user->id || auth()->user()->role === 1)
                    <a href="{{ route('profile_edit',['username' => $user->username]) }}">Edit</a>
                    @endif
                @endauth
            </button>
        </div>        
        <div class="mt-6 flex flex-col md:flex-row -mx-3">
            <div class="md:flex-1 px-3">
                <div class="mb-4">
                    <img src="{{$user->image}}" alt="User Profile"
                        class="rounded-full w-32 h-32 md:w-48 md:h-48 mx-auto">
                </div>
                <div class="flex items-end mb-4 justify-center">
                    <label class="text-xl font-bold text-gray-700">{{$user->display_name}}</label>
                    <span class="ml-2 text-xs text-gray-600 pb-1">
                        {{$user->is_private ? 'Private' : 'Public'}}
                    </span>
                </div>
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Username</label>
                    <div class="font-semibold text-gray-800">{{$user->username}}</div>
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
    @if(count($posts) == 0 || (!auth()->user()->friend($user) && $user->is_private))
    <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No posts found.</p>
    @else
    @for($i = 0; $i < count($posts); $i++) @include('partials.post_card', ['post'=> $posts[$i]])
        @endfor
        @endif
</div>