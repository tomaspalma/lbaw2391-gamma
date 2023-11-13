@extends('layouts.head')

<head>
    @vite('resources/css/app.css')

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <div class="border border-black rounded-md p-8 my-8 max-w-3xl mx-auto rounded-md shadow-md">
        <div class="flex justify-between items-center">
            <h2 class="text-4xl font-bold">{{ $post->title }}</h2>
            <span class="text-gray-600">
                <time>{{ $post->date }}</time>
            </span>
        </div>

        <div class="flex space-x-4 mt-4">
            <img src="{{ $post->owner->image ?? 'hello' }}" class="rounded-full w-10 h-10">
            <a class="text-lg text-gray-600 hover:underline" href="">{{ $post->owner->username }}</a>
            @if($post->group)
                <a class="text-lg text-gray-600 hover:underline">@ {{ $post->group->name }}</a>
            @endif
        </div>

        <div class="mt-6 prose max-w-full">
            {{ $post->content }}
        </div>

        <div class="post-action-bar mt-4 flex justify-between items-center">
            <!-- Add your post action buttons here -->
        </div>
    </div>
</main>

