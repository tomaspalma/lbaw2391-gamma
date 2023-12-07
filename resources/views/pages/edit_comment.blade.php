@extends('layouts.head')

<head>
    @vite('resources/css/app.css')
</head>

@include('partials.navbar')

<main class="center">
    <div class="border border-black rounded-md p-8 my-8 max-w-3xl mx-auto shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Edit Comment</h2>

        <form action="{{ route('comment.update', $comment->id) }}" method="post" class="grid grid-cols-2 gap-4">
            @method('PUT')    
            @csrf

            <div class="mb-4 col-span-2">
                <label for="content" class="block text-sm font-medium text-gray-600">Content:</label>
                <textarea name="content" id="content" rows="5" class="mt-1 p-2 border border-gray-300 rounded-md w-full resize-none" required>{{ old('content', $comment->content) }}</textarea>
            </div>

            <div class="col-span-2">
                <button type="submit" class="bg-black text-white py-2 px-4 rounded-md">Update Comment</button>
            </div>
        </form>
    </div>
</main>