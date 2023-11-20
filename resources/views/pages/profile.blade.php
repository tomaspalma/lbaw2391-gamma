@extends('layouts.head')


<head>
    @vite(['resources/css/app.css', 'resources/js/components/dropdown_dots.js', 'resources/js/profile/delete.js'])
</head>

@include('partials.navbar')

@include('partials.confirm_modal')

<div class="max-w-screen-md mx-auto pb-4">
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6 border border-black">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-700 text-center items-center">User Profile</h2>
            @can('update', $user)
            <div class="relative inline-block text-left">
                <button id="dropdownButton" class="text-black font-bold py-2 px-4 rounded">
                    {{-- <i class="fas fa-ellipsis-v"></i> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                      </svg>                      
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
    @forelse($posts as $post)
        @include('partials.post_card', ['post'=> $post])
    @empty <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No posts found.</p>
    @endforelse
</div>