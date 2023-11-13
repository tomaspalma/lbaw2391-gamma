@extends('layouts.head')

<head>
    @vite('resources/css/app.css')

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <div class="border border-black rounded-md p-8 my-8 max-w-3xl mx-auto rounded-md shadow-md">
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
                    <option value="0">Public</option>
                    <option value="1">Private</option>
                </select>
            </div>

            <div class="mb-4 col-span-2">
                <label for="content" class="block text-sm font-medium text-gray-600">Content:</label>
                <textarea name="content" id="content" rows="5" class="mt-1 p-2 border border-gray-300 rounded-md w-full resize-none" required></textarea>
            </div>

            <div class="col-span-2">
                <button type="submit" class="bg-black text-white py-2 px-4 rounded-md">Create Post</button>
            </div>
        </form>
    </div>
</main>

