@extends('layouts.head')

<head>
    @vite('resources/css/app.css')

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <li class="flex w-1/2 {{ $feed === 'popular' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/feed" class="hover:underline">Popular</a>
        </li>

        <li class="flex w-1/2 {{ $feed === 'personal' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/feed/personal" class="hover:underline">Personalized</a>
        </li>
    </ul>


    @if(count($posts) == 0)
    <p class="text-center">No posts found.</p>
    @else
    @for($i = 0; $i < count($posts); $i++) @if(!($posts[$i]->owner->is_private))
        @include('partials.post_card', ['post'=> $posts[$i]])
        @endif
        @endfor
        @endif

</main>
