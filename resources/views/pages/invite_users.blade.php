@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/faq.js'])
    <title>{{ config('app.name', 'Laravel') }} | Invite to Group</title>

</head>

@include('partials.navbar')

<main class="center">

<ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <li class="flex w-1/2 {{ $feed === 'invite' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/group/{{$group->id}}/invite" class="hover:underline text-lg font-bold">New Invite</a>
        </li>
            

        <li class="flex w-1/2 {{ $feed === 'invited' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/group/{{$group->id}}/invites" class="hover:underline text-lg font-bold">Pending Invites</a>
        </li>
        </ul>

    @if($feed === 'invite')
        <div class="flex items-center justify-center my-4">
            <input type="search" name="username" placeholder="Enter username" class="p-2 border rounded shadow-sm">
        </div>
        @foreach($users as $user)
            @include('partials.user_card', ['user' => $user, 'adminView' => false, 'is_group' => false, 'group' => $group, 'invite' => true])
        @endforeach
    
    @else
        @foreach($invites as $invite)
            <p>{{$invite}}</p>
        @endforeach
    @endif



</main>
