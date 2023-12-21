@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/group/edit.js'])

    <title>{{ config('app.name', 'Laravel') }} | Editing Group {{ $group->name }}</title>
    <link href="{{ url('css/post.css') }}" rel="stylesheet">

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Edit Group",
    'url' => $url,
    'image' => $logo
    ])

</head>

<body>
    @include('partials.navbar')

    <div class="container mx-auto mt-8 max-w-screen-md ounded-lg shadow-lg p-6 border md:mb-12">
        <div class="flex justify-center items-center">
            <h2 class="text-2xl font-bold mb-4 justi">Edit Group {{ $group->name }}</h1>
        </div>
        <form action="{{ route('group.update', $group->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <img src="{{ $group->getBannerImage() }}" alt="Banner Image" id="bannerPreview" class="w-full h-32 md:h-56 object-cover max-w-full">
            <img src="{{ $group->getGroupImage('medium') }}" alt="Group Image" id="imagePreview" class="w-24 h-24 md:w-32 md:h-32 ml-4 object-cover rounded-full -mt-14 border-2 border-white max-w-full">

            <div class="flex flex-row w-full items-center mb-2"> 
                <input type="file" name="image" id="image" class="hidden" onchange="document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0])">
                <button type="button" class="form-button py-2 px-4 rounded-md m-2" onclick="document.getElementById('image').click()">Upload Image</button>
                <input type="file" name="banner" id="banner" class="hidden" onchange="document.getElementById('bannerPreview').src = window.URL.createObjectURL(this.files[0])">
                <button type="button" class="form-button py-2 px-4 rounded-md m-2" onclick="document.getElementById('banner').click()">Upload Banner</button>
            </div>

            @error('image')
                <p class="text-red-500 text-sm">{{ $message }}. Max size is 2mb.</p>
            @enderror
            @error('banner')
                <p class="text-red-500 text-sm">{{ $message }}. Max size is 2mb.</p>
            @enderror

            <div class="mb-4">
                <input type="hidden" id="old_name" name="old_name" value="{{ $group->name }}">
                <label for="name" class="text-sm text-gray-600">Group Name <span class="required-input">*</span></label>
                <input type="text" id="name" name="name" value="{{ $group->name }}" class="w-full border p-2">
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <p id="name_error" class="text-red-500 text-sm hidden">Group name already taken.</p>
            </div>

            <div class="mb-4">
                <label for="description" class="text-sm text-gray-600">Group Description <span class="required-input">*</span></label>
                <textarea id="description" name="description" class="w-full border p-2">{{ $group->description }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <label for="privacy" class="text-sm text-gray-600">Privacy</label>
            <select id="privacy" name="privacy" class="w-full border p-2 mb-4">
                <option value="public" {{ $group->is_private ? '' : 'selected' }}>Public</option>
                <option value="private" {{ $group->is_private ? 'selected' : '' }}>Private</option>
            </select>


            <button type="submit" id="submit" class="form-button py-2 px-4 rounded-md">
                Submit
            </button>
            <a href="{{ route('groupPosts', $group->id) }}">
                <button type="button" class="form-button-red py-2 px-4 rounded-md hover:no-underline font-bold">
                    Cancel
                </button>
            </a>
        </form>
    </div>
    @include('partials.footer')
</body>

