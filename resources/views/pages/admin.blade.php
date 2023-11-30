@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search/admin_user_search.js', 'resources/js/admin/user/block.js'])
</head>

@include('partials.navbar')

<main class="center mx-4">
    @include('partials.admin.common')
    <ul class="tab-container">
        <li class="flex w-1/2 p-2 justify-center toggled-tab">
            Dashboard
        </li>

        @auth
        <li class="flex w-1/2 p-2 justify-center">
            <a href="/admin/user/appeals" class="hover:underline">Appeals</a>
        </li>
        @endauth
    </ul>

    <div class="flex flex-col align-middle justify-center" id="admin-search-user-results">
        @for($i = 0; $i < count($users); $i++) @if($users[$i]->id !== 0 && $users[$i]->role !== 1)
            @include('partials.user_card', [ 'user'=> $users[$i], 'adminView' => true])
            @endif
            @endfor
    </div>
    @include('partials.confirm_modal')
