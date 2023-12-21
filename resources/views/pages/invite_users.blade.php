@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/faq.js', 'resources/js/group/invites.js'])
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
            <input data-entity-group="{{$group->id}}" id="invite-user-search" type="search" name="username" placeholder="Enter username" class="p-2 border rounded-md shadow-sm">
        </div>
        
        <div id="invitees">
        @if(count($users) === 0)
            <p class="text-center">No users found.</p>
        @endif
        @foreach($users as $user)
            @include('partials.user_card', ['user' => $user, 'adminView' => false, 'is_group' => false, 'group' => $group, 'invite' => true])
        @endforeach
        </div>
    
    @else
        @if(count($invites) === 0)
            <p class="text-center">No pending invites found.</p>
        @endif
        @foreach($invites as $invite)
            @include('partials.user_card', ['user' => $invite->user, 'adminView' => false, 'is_group' => false, 'pending_invite' => $invite])
        @endforeach
    @endif



</main>

@include('partials.snackbar')
