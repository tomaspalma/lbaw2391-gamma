@extends('layouts.head')

<body>
    @include('partials.navbar')

    <div class="container mx-auto mt-8 max-w-screen-md ounded-lg shadow-lg p-6 border">
        <div class="flex justify-center items-center">
            <h2 class="text-2xl font-bold mb-4 justi">Create Group</h1>
        </div>
        <form action="{{ route('group.create') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <img src="{{ $bannerImage }}" alt="Banner Image" id="bannerPreview" class="w-full h-32 md:h-56 object-cover max-w-full">
            <img src="{{ $groupImage }}" alt="Group Image" id="imagePreview" class="w-24 h-24 md:w-32 md:h-32 ml-4 object-cover rounded-full -mt-14 border-2 border-white max-w-full">
            <div class="flex flex-row w-full items-center mb-2"> 
                <input type="file" name="image" id="image" class="hidden" onchange="document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0])">
                <button type="button" class=" bg-black text-white px-4 py-2 rounded" onclick="document.getElementById('image').click()">Upload Image</button>
                <input type="file" name="banner" id="banner" class="hidden" onchange="document.getElementById('bannerPreview').src = window.URL.createObjectURL(this.files[0])">
                <button type="button" class=" bg-black text-white m-2 px-4 py-2 rounded" onclick="document.getElementById('banner').click()">Upload Banner</button>
            </div>
            <label for="name" class="text-sm text-gray-600">Group Name</label>
            <input type="text" id="name" name="name" class="w-full border p-2 mb-4">

            <label for="description" class="text-sm text-gray-600">Group Description</label>
            <textarea id="description" name="description" class="w-full border p-2 mb-4"></textarea>

            <label for="privacy" class="text-sm text-gray-600">Privacy</label>
            <select id="privacy" name="privacy" class="w-full border p-2 mb-4">
                <option value=0 selected>Public</option>
                <option value=1>Private</option>
            </select>

            <button type="submit" class="bg-black hover:bg-black text-white font-bold py-2 px-4 rounded">
                Submit
            </button>
            <a href="{{ route('feed') }}" class="bg-white hover:bg-gray-100 text-black hover:no-underline font-bold py-2 px-4 rounded">Cancel</a>
        </form>
    </div>
</body>

@include('partials.footer')
