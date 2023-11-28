@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/post/delete.js'])

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')


<main class = "center">
    @can('create', App\Models\Post::class)
    <a href="{{ route('post.createForm') }}" class="my-4 block mx-auto px-4 py-2 bg-black text-white text-center rounded">Create Post</a> 
    @endcan
    <ul class="tab-container center justify-center flex border border-black rounded shadow my-4">
        <li class="flex w-1/2 {{ $feed === 'posts' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/group/{{$group}}" class="hover:underline">Posts</a>
        </li>
        <li class="flex w-1/2 {{ $feed === 'members' ? 'border-t-4 border-black' : '' }} p-2 justify-center">
            <a href="/group/{{$group}}/members" class="hover:underline">Members</a>
        </li>
    </ul>


    @if($feed === 'posts')
        @if($posts->count() == 0)
            <p class="text-center">No posts found.</p>
        @else
            @for($i = 0; $i < $posts->count(); $i++) 
                @include('partials.post_card', ['post'=> $posts->get()[$i]])
            @endfor
        @endif
    @endif


</main>