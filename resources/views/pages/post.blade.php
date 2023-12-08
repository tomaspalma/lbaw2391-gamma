@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js' , 'resources/js/post/delete.js', 'resources/js/comment/add.js' , 'resources/js/comment/delete.js', 'resources/js/post/scroll.js'])

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <div data-entity="post" data-entity-id="{{$post->id}}" post-id="{{$post->id}}" class="border border-black rounded-md p-8 my-8 max-w-3xl mx-auto shadow-md">
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
            <a class="text-lg text-gray-600 hover:underline" href="{{route('groupPosts', ['id' => $post->group_id])}}">@ {{ $post->group->name }}</a>
            @endif
        </div>

        <div class="mt-6 prose max-w-full">
            {{ $post->content }}
        </div>

        <div class="post-action-bar mt-4 flex justify-between items-center">
            @php
            $f = function($user, $post) {
            return $user->post_reaction($post);
            }
            @endphp
            @auth
            @include('partials.reactions', ['entity' => $post, 'entity_function' => $f, 'entity_name' => 'post'])
            @endauth
        </div>

        @can('update', $post)
        <div class="flex justify-between items-center mt-4">
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
            @include('partials.comment_card', ['comment'=> $comment])
            @empty
            <p id="no-comment">No comments yet.</p>
            @endforelse
        </div>

    </section>
    @include('partials.confirm_modal')
</main>
