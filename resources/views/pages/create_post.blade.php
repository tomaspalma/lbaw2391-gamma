@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/post/poll.js'])

    <title>{{ config('app.name', 'Laravel') }} | Create post</title>
    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <div class="border border-black p-8 my-8 max-w-3xl mx-auto rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Create a New Post</h2>

        <form action="{{ route('post.create') }}" method="post" class="grid grid-cols-2 gap-4">
            @csrf

            <div class="mb-4 col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-600">Title:</label>
                <input type="text" name="title" id="title" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
            </div>  

            <div class="mb-4">
                <label for="group" class="block text-sm font-medium text-gray-600">Group:</label>
                <select name="group" id="group" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    <option value="">None</option>
                    @foreach ($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="is_private" class="block text-sm font-medium text-gray-600">Privacy:</label>
                <select name="is_private" id="is_private" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                    @can('publicPost', App\Models\Post::class)
                    <option value="0">Public</option>
                    @else
                    <option value="0" disabled>Public</option>
                    @endcan
                    <option value="1">Private</option>
                </select>
            </div>

            <div class="mb-4 col-span-2">
                <label for="content" class="block text-sm font-medium text-gray-600">Content:</label>
                <textarea name="content" id="content" rows="5" class="mt-1 p-2 border border-gray-300 rounded-md w-full resize-none" required></textarea>
            </div>

            <div class="mb-4 col-span-2">
                <button id="add-poll-btn" name="content" class="mt-1 p-2 border border-gray-300 hover:bg-black hover:text-white transition-colors rounded-md w-full resize-none">
                    Add poll +
                </button>

                <article id="poll-creation" class="mt-1 p-2 rounded-md w-full resize-none hidden">
                    <h1 class="text-center text-xl font-bold">Create poll</h1>
                    <h2>Options</h2>
                    <div id="options">
                        <div class="flex flex-row space-x-1">
                            <input placeholder="Option" type="text" name="poll_options[]" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                            <button class="remove-option-btn mt-1 p-2 border border-gray-300 hover:bg-black hover:text-white transition-colors rounded-md">
                                -
                            </button>
                        </div>
                    </div>
                    <button id="add-poll-option-btn" class="mt-1 p-2 border border-gray-300 hover:bg-black hover:text-white transition-colors rounded-md w-full resize-none">
                        Add option +
                    </button>
                </article>
            </div>

            <div class="col-span-2">
                <button type="submit" class="bg-black text-white py-2 px-4 rounded-md">Create Post</button>
            </div>
        </form>
    </div>
</main>

@include('partials.footer')
