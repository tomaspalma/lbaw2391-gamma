@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/post/delete.js', 'resources/js/group_management/scroll.js', 'resources/js/group/group_requests.js', 'resources/js/group/invites.js'])

    <title>{{ config('app.name', 'Laravel') }} | Your groups</title>

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
    <link href="{{url('css/group.css')}}" rel="stylesheet">

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Your groups",
    'url' => $url,
    'image' => $logo
    ])


    <script src="https://kit.fontawesome.com/38229b6c34.js" crossorigin="anonymous"></script>
</head>

@include('partials.navbar')

<main class="center">
@can('create', App\Models\Group::class)
    <a href="{{ route('group.createForm') }}" class="my-4 block mx-auto px-4 py-2 form-button text-center rounded hover:no-underline">Create Group</a>
@endcan
<ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <li class="flex w-1/3 {{ $feed === 'groups' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/groups" class="hover:underline text-lg font-bold">Groups ({{$groupsNormal->count() + $groupsOwner->count()}})</a>
        </li>

        <li class="flex w-1/3 {{ $feed === 'invites' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/groups/invites" class="hover:underline text-lg font-bold">Invites ({{sizeof($invites)}})</a>
        </li>

        @if(Auth::user()->groups('owner')->count() > 0)
        <li class="flex w-1/3 {{ $feed === 'requests' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/groups/requests" class="hover:underline text-lg font-bold">Group Requests ({{sizeof($requests)}})</a>
        </li>
        @endif
</ul>

<div id="content">
@if($feed === 'groups')
    @if($groupsNormal->count() == 0 && $groupsOwner->count() == 0)
        <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No groups found.</p>

    @else
        <div id="groups">
        @if($groupsOwner->count() > 0)
            @for($i = 0; $i < $groupsOwner->count(); $i++)
                @include('partials.group_card', ['group'=> $groupsOwner[$i], 'owner' => true])
            @endfor
        @endif

        @if($groupsNormal->count() > 0)

            @for($i = 0; $i < $groupsNormal->count(); $i++)
                @include('partials.group_card', ['group'=> $groupsNormal[$i], 'owner' => false])
            @endfor
        @endif
        </div>
    @endif

@elseif ($feed === 'requests')
    @if(sizeof($requests) > 0)
        @for($i = 0; $i < sizeof($requests); $i++)
            @include('partials.group_requests_card', ['request'=> $requests[$i]])
        @endfor
    @else
    <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No requests found.</p>
    @endif
@elseif ($feed === 'invites')
    @if(sizeof($invites) > 0)
        @foreach($invites as $invite)
            @include('partials.user_card', ['user' => $invite->user, 'adminView' => false, 'is_group' => false, 'received_invite' => $invite])
        @endforeach
    @else
        <p class="text-center align-middle text-2xl font-semibold mt-20 text-gray-700">No invites found.</p>
    @endif
@endif
</div>

    <br>

</main>

@include('partials.snackbar')
@include('partials.footer')
