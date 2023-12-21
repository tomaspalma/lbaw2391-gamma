@extends('layouts.head')

<head>
    @vite('resources/css/app.css')

    <title>{{ config('app.name', 'Laravel') }} | Edit post</title>
    <link href="{{ url('css/post.css') }}" rel="stylesheet">

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Edit Post",
    'url' => $url,
    'image' => $logo
    ])

</head>

@include('partials.navbar')

<main class="center">
    <div class="border border-black rounded-md p-8 my-8 max-w-3xl mx-auto rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Edit Post</h2>

        <form action="{{ route('post.update', $post->id) }}" method="post" class="grid grid-cols-2 gap-4">
            @method('PUT')    
            @csrf

            <div class="mb-4 col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-600">Title: <span class="required-input">*</span></label>
                <input type="text" name="title" id="title" class="mt-1 p-2 border border-gray-300 rounded-md w-full" value="{{ old('title', $post->title) }}" required>
            </div>

            <div class="mb-4">
                <label for="group" class="block text-sm font-medium text-gray-600">Group:</label>
                <select name="group" id="group" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    <option value="">None</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" {{ $group->id == $post->group_id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="is_private" class="block text-sm font-medium text-gray-600">Privacy:</label>
                <select name="is_private" id="is_private" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                    @can('publicPost', App\Models\Post::class)
                        <option value="0" {{ $post->is_private == 0 ? 'selected' : '' }}>Public</option>
                    @else
                        <option value="0" disabled>Public</option>
                    @endcan
                    <option value="1" {{ $post->is_private == 1 ? 'selected' : '' }}>Private</option>
                </select>
            </div>

            <div class="mb-4 col-span-2">
                <label for="content" class="block text-sm font-medium text-gray-600">Content: <span class="required-input">*</span></label>
                <textarea name="content" id="content" rows="5" class="mt-1 p-2 border border-gray-300 rounded-md w-full resize-none" required>{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="col-span-2">
                <button type="submit" class="form-button bg-black text-white py-2 px-4 rounded-md">Update Post</button>
            </div>
        </form>
    </div>
</main>

@include('partials.footer')
