@extends('layouts.head')

<body>
    @include('partials.navbar')

    <div class="container mx-auto mt-8 max-w-screen-md ounded-lg shadow-lg p-6 border">
        <div class="flex justify-center items-center">
            <h2 class="text-2xl font-bold mb-4 justi">Edit Group</h1>
        </div>
        <form action="{{ route('group.update', $group->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="flex flex-col w-full items-center mb-2"> 
                <img src="{{ $group->getBannerImage() }}" alt="Banner Image" id="bannerPreview" class="my-2 h-20 w-60 md:h-32 md:w-96 object-cover">
                <input type="file" name="banner" id="banner" class="hidden" onchange="document.getElementById('bannerPreview').src = window.URL.createObjectURL(this.files[0])">
                <button type="button" class=" bg-gray-600 text-white px-4 py-2 rounded" onclick="document.getElementById('banner').click()">Upload Banner</button>
            </div>
            <div class="flex flex-col w-full items-center mb-2"> 
                <img src="{{ $group->getGroupImage() }}" alt="Group Image" id="imagePreview" class="my-2 rounded-full w-20 h-20 md:w-32 md:h-32 object-cover">
                <input type="file" name="image" id="image" class="hidden" onchange="document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0])">
                <button type="button" class=" bg-gray-600 text-white px-4 py-2 rounded" onclick="document.getElementById('image').click()">Upload Image</button>
            </div>
            <label for="name" class="text-sm text-gray-600">Group Name</label>
            <input type="text" id="name" name="name" value="{{ $group->name }}" class="w-full border p-2 mb-4">

            <label for="description" class="text-sm text-gray-600">Group Description</label>
            <textarea id="description" name="description" class="w-full border p-2 mb-4">{{ $group->description }}</textarea>

            <label for="privacy" class="text-sm text-gray-600">Privacy</label>
            <select id="privacy" name="privacy" class="w-full border p-2 mb-4">
                <option value="public" {{ $group->is_private ? '' : 'selected' }}>Public</option>
                <option value="private" {{ $group->is_private ? 'selected' : '' }}>Private</option>
            </select>

            <button type="submit" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded">
                Submit
            </button>
            <a href="{{ route('groupPosts', $group->id) }}">
                <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </button>
            </a>
        </form>
    </div>
</body>

