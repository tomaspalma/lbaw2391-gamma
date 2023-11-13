@extends('layouts.head')

<head>
    @vite('resources/css/app.css')

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <div class="border border-black rounded-md p-8 my-8 max-w-3xl mx-auto rounded-md shadow-md">
        <h2 class="text-4xl font-bold mb-4">{{ $post->title }}</h2>
        <p class="text-lg text-gray-600">By {{ $post->owner->username }}</p>

        <div class="mt-6 prose max-w-full">
            {!! $post->content !!}
        </div>
    </div>
</main>
