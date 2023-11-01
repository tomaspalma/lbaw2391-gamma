@extends('layouts.head')

<head>
    @vite('resources/css/app.css')

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

<main class="center">
    <ul class="tab-container center justify-center flex space-x-10 border border-black rounded shadow my-4">
        <li class="flex w-1/2 {{ $feed === 'popular' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/feed">Popular</a>
        </li>

        <li class="flex w-1/2 {{ $feed === 'personal' ? 'border-t-4 border-black' : '' }} p-2 p-2 justify-center">
            <a href="/feed/personal">Personalized</a>
        </li>
    </ul>
    
    @if(count($posts) == 0) 
        <p class="text-center">No posts found.</p>
    @else
        @for($i = 0; $i < count($posts); $i++)
            @include('partials.post_card', ['post' => $posts[$i]])
        @endfor
    @endif

</main>
