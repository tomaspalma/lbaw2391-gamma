@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/post/delete.js', 'resources/js/group/scroll.js'])

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
    <link href="{{url('css/group.css')}}" rel="stylesheet">

    <script src="https://kit.fontawesome.com/38229b6c34.js" crossorigin="anonymous"></script>
</head>

@include('partials.navbar')

<ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <li class="flex w-1/2 {{ $feed === 'groups' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/groups" class="hover:underline">Groups</a>
        </li>

        <li class="flex w-1/2 {{ $feed === 'requests' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/groups/requests" class="hover:underline">Group Requests</a>
        </li>
</ul>

@if($feed === 'groups')

    @if($groupsNormal->count() == 0 && $groupsOwner->count() == 0)
        <p>Não estás em nenumh grupo, burro</p>

    @else
        @if($groupsOwner->count() > 0)
            @for($i = 0; $i < $groupsOwner->count(); $i++)
                @include('partials.group_card', ['group'=> $groupsOwner->get()[$i], 'owner' => true])
            @endfor
        @endif

        @if($groupsNormal->count() > 0)

            @for($i = 0; $i < $groupsNormal->count(); $i++)
                @include('partials.group_card', ['group'=> $groupsNormal->get()[$i], 'owner' => false])
            @endfor
        @endif
    @endif

@else
    @if(sizeof($requests) > 0)
        @for($i = 0; $i < sizeof($requests); $i++)
            @include('partials.group_requests_card', ['request'=> $requests[$i]])
        @endfor
    @endif
@endif