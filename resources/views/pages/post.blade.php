@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/post/delete.js'])

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <div post-id="{{$post->id}}" class="border border-black rounded-md p-8 my-8 max-w-3xl mx-auto rounded-md shadow-md">
        <div class="flex justify-between items-center">
            <h2 class="text-4xl font-bold">{{ $post->title }}</h2>
            <span class="text-gray-600">
                <time>{{ $post->format_date() }}</time>
            </span>
        </div>

        <div class="flex space-x-4 mt-4">
            <img src="{{ $post->owner->getProfileImage() ?? 'hello' }}" class="rounded-full w-10 h-10">
            <a class="text-lg text-gray-600 hover:underline" href="{{ route('profile',['username' => $post->owner->username]) }}">{{ $post->owner->username }}</a>
            @if($post->group)
            <a class="text-lg text-gray-600 hover:underline">@ {{ $post->group->name }}</a>
            @endif
        </div>

        <div class="mt-6 prose max-w-full">
            {{ $post->content }}
        </div>

        <div class="post-action-bar mt-4 flex justify-between items-center">

        </div>

        <div class="flex justify-between items-center">
            @canany(['update', 'delete'], $post)
                <a href="{{ route('post.update', $post->id) }}" class="bg-black text-white py-2 px-4 rounded-md">Edit Post</a>

                <button type="submit" class="delete-post-button bg-red-500 text-white py-2 px-4 rounded-md">Delete Post</button>
            @endcanany
        </div>
    </div>

    <section class="border border-black p-4 my-4 max-w-3xl mx-auto rounded-md shadow-md">
        <h3 class="text-2xl font-bold mb-4">Comments</h3>

        @forelse($comments as $comment)
        <div class="flex space-x-4">
            <img src="{{ $comment->author->getProfileImage() ?? 'hello' }}" class="rounded-full w-8 h-8">
            <div>
                <p class="text-gray-600">{{ $comment->owner->username }}</p>
                <p>{{ $comment->content }}</p>
            </div>
        </div>
        <hr class="my-2">
        @empty
        <p>No comments yet.</p>
        @endforelse
    </section>
    @include('partials.confirm_modal')
</main>
