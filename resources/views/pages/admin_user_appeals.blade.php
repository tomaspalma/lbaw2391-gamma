@extends('layouts.head')

<head>
    @vite(['resources/js/search/admin_user_search.js', 'resources/js/admin/user/remove_appeal.js'])
</head>

@include('partials.navbar')

<main class="center mx-4">
    @include('partials.admin.common')
    <ul class="tab-container">
        <li class="flex w-1/2 p-2 justify-center">
            <a href="/admin/user">Dashboard</a>
        </li>

        @auth
        <li class="flex w-1/2 p-2 justify-center toggled-tab">
            <a href="/admin/user/appeals">
                Appeals (<span id="appeal-counter">{{$appeal_number}}</span>)
                <F11>
            </a>
        </li>
        @endauth
    </ul>
    <div id="content">
        @if(count($appeals) === 0)
        <p class="text-center">No appeals found.</p>
        @endif
        <div class="flex flex-col align-middle justify-center" id="admin-search-user-results">
            @foreach ($appeals as $appeal)
            @include('partials.user_card', [ 'user'=> $appeal->appban->user, 'adminView' => true, 'appealView' => true])
            @endforeach
        </div>
    </div>
</main>
@include('partials.confirm_modal')
