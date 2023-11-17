@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search/admin_user_search.js', 'resources/js/admin/user/block.js'])
</head>

@include('partials.navbar')

<main class="center mx-4">
    <div class="flex justify-center mb-2">
        <a target="_blank" href="{{url('/admin/user/create')}}">Create user</a>
    </div>
    <div class="flex justify-center">
        <form id="search-form" class="w-1/2 ">
            <input name="search" type="text" id="search-user-admin" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search an user...">
        </form>
    </div>
    <div class="flex flex-col align-middle justify-center" id="admin-search-user-results">
        @for($i = 0; $i < count($users); $i++) @if($users[$i]->id !== 0 && $users[$i]->role !== 1)
            @include('partials.user_card', [ 'user'=> $users[$i], 'adminView' => true])
            @endif
            @endfor
    </div>
    @include('partials.confirm_modal')
</main>
