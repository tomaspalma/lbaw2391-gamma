@extends('layouts.head')

<head>
    @vite('resources/css/app.css')

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <div class="post-container">
            <h2>{{ $post->title }}</h2>
            <p class="author">By {{ $post->owner->username }}</p>
            
            <div class="content">
                {!! $post->content !!}
            </div>
    </div>

</main>
