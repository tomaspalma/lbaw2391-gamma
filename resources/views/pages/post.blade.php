@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/post/delete.js', 'resources/js/comment/add.js', 'resources/js/comment/delete.js'])

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
            @auth
                @include('partials.reactions', ['entity' => $post]) 
            @endauth
        </div>
        
        <div class="my-4">
            @foreach ($post->reactionsMap() as $icon => $metadata)
                <i class="fa-solid {{$icon}} {{$metadata[1]}}"></i>
                {{$metadata[0]}}
            @endforeach
        </div>

        @can('update', $post)
        <div class="flex justify-between items-center">
                <a href="{{ route('post.update', $post->id) }}" class="bg-black text-white py-2 px-4 rounded-md">Edit Post</a>
                <button type="submit" class="delete-post-button bg-red-500 text-white py-2 px-4 rounded-md">Delete Post</button>
            </div>
        @elsecan('delete', $post)
            <div class="flex justify-end items-center">
                <button type="submit" class="delete-post-button bg-red-500 text-white py-2 px-4 rounded-md">Delete Post</button>
            </div>
        @endcan
    </div>

    <section class="border border-black p-4 my-4 max-w-3xl mx-auto rounded-md shadow-md">
        <h3 class="text-2xl font-bold mb-4">Comments</h3>

        @can('create', App\Models\Comment::class)
        <form id="comment-form" class="flex flex-col space-y-4">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <textarea name="content" class="border border-gray-300 rounded-md p-2" placeholder="Write a comment..."></textarea>
            <button type="button" id="comment-button" class="bg-black text-white py-2 px-4 rounded-md">Comment</button>
        </form>
        @endcan

        <div id="comments" class="mt-4">
            @forelse($comments as $comment)
            <div class="flex max-w-full overflow-auto space-x-4">
                <img src="{{ $comment->owner->getProfileImage() ?? 'hello' }}" class="rounded-full self-center w-8 h-8">
                <div class="grow">
                    <p class="text-gray-600">{{ $comment->owner->username }}</p>
                    <p class="">{{ $comment->content }}</p>
                </div>
                    @can('delete', $comment)
                        <button type="button" class="delete-comment-button bg-red-500 text-white self-center py-1 px-2 rounded-md" comment-id="{{ $comment->id }}">Delete</button>
                    @endcan
            </div>
            <hr class="my-2">
            @empty
            <p id="no-comment">No comments yet.</p>
            @endforelse
        </div>
    </section>
    @include('partials.confirm_modal')
</main>
