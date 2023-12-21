@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/post/tag.js'])

    <title>{{ config('app.name', 'Laravel') }} | Edit post</title>
    
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

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
    <div class="border border-black p-8 my-8 max-w-3xl mx-auto rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Edit Post</h2>

        <form action="{{ route('post.update', $post->id) }}" method="post" class="grid grid-cols-2 gap-4" enctype="multipart/form-data">
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
                <textarea name="content" id="content" rows="5" class="mt-1 p-2 border border-gray-300 rounded-md w-full break-words resize-none" required>{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="mb-4 col-span-2">
            <input type="hidden" name="remove_attachment" id="remove_attachment" value="0"> 
            @if($attachment !== null)
                <label for="attachment" class="block text-sm font-medium mt-4 text-gray-600">Image:</label>
                <img id="image-preview" src="{{ $attachment }}" class="my-2 mx-auto w-1/2" alt="Image preview"/>
                <input type="file" name="attachment" id="attachment" class="hidden" onchange="document.getElementById('image-preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('remove-img').classList.remove('hidden'); document.getElementById('image-btn').classList.add('hidden'); document.getElementById('image-preview').classList.remove('hidden');">
                <button type="button" id="image-btn" class="form-button py-2 px-4 rounded-md hidden" onclick="document.getElementById('attachment').click()">
                    Upload
                </button>
                <button type="button" id="remove-img" class="form-button py-2 px-4 rounded-md" onclick="document.getElementById('attachment').value = ''; document.getElementById('image-preview').src = '#'; document.getElementById('remove-img').classList.add('hidden'); document.getElementById('image-btn').classList.remove('hidden'); document.getElementById('image-preview').classList.add('hidden'); document.getElementById('remove_attachment').value = '1'">
                    Remove
                </button>
            @else
                <label for="attachment" class="block text-sm font-medium text-gray-600">Image:</label>
                <img id="image-preview" src="#" class="my-2 mx-auto w-1/2 hidden" alt="Image preview"/>
                <input type="file" name="attachment" id="attachment" class="hidden" onchange="document.getElementById('image-preview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('remove-img').classList.remove('hidden'); document.getElementById('image-btn').classList.add('hidden'); document.getElementById('image-preview').classList.remove('hidden');">
                <button type="button" id="image-btn" class="form-button py-2 px-4 rounded-md" onclick="document.getElementById('attachment').click()">
                    Upload
                </button>
                <button type="button" id="remove-img" class="form-button py-2 px-4 rounded-md hidden" onclick="document.getElementById('attachment').value = ''; document.getElementById('image-preview').src = '#'; document.getElementById('remove-img').classList.add('hidden'); document.getElementById('image-btn').classList.remove('hidden'); document.getElementById('image-preview').classList.add('hidden'); document.getElementById('remove_attachment').value = '1'">
                    Remove
                </button>
            @endif
                @error('attachment')
                    <p class="text-red-500 text-sm">{{ $message }}. Max size is 2mb.</p>
                @enderror
            </div>

            <div class="col-span-2 mt-4">
                <button type="submit" class="form-button bg-black text-white py-2 px-4 rounded-md">Update Post</button>
            </div>
        </form>
    </div>
</main>

@include('partials.footer')
