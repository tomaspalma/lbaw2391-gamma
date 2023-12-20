@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/faq.js'])
    <title>{{ config('app.name', 'Laravel') }} | Invite to Group</title>

</head>

@include('partials.navbar')

<main class="center">

<ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <li class="flex w-1/2 {{ $feed === 'groups' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/group/{{$group->id}}/invite" class="hover:underline text-lg font-bold">Groups</a>
        </li>
            

        <li class="flex w-1/2 {{ $feed === 'requests' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/group/{{$group->id}}/invite" class="hover:underline text-lg font-bold">Groups</a>
        </li>
</ul>

</main>
