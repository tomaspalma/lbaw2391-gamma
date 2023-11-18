@extends('layouts.head')

<head>
    @vite(['resources/css/app.css','resources/js/edit_profile.js'])
</head>

@include('partials.navbar')

<div class="max-w-screen-md mx-auto p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6 border border-black">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-700 mx-auto">Edit Profile</h2>
        </div>
        <form action="{{ route('profile_update', ['username' => $user->username]) }}" method="post"
            enctype="multipart/form-data" class="mt-6 flex flex-col md:flex-row -mx-3" id="edit_profile_form">
            @csrf
            @method('PUT')

            <div class="md:flex-1 px-3">
                <div class="mb-4">                    
                    <img src="{{ $user->image }}" alt="Profile Image" id="imagePreview"
                        class="rounded-full w-32 h-32 md:w-48 md:h-48 cursor-pointer mx-auto" onclick="document.getElementById('imageInput').click()">
                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden"
                        onchange="document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0])">
                </div>
                <div class="mb-4">
                    <label for="display_name" class="text-sm text-gray-600">Display Name</label>
                    <input type="text" name="display_name" id="display_name" value="{{ $user->display_name }}"
                        class="mt-1 py-2 px-3 border rounded-md w-full">
                </div>
                <div class="">
                    <input type="hidden" id="old_username" value="{{ $user->username }}">
                    <label for="username" class="text-sm text-gray-600">Username</label>
                    <input type="text" name="username" id="username" value="{{ $user->username }}"
                        class="mt-1 py-2 px-3 border rounded-md w-full">
                </div>
                <div id="username-error" class="text-red-700 text-sm mb-4"></div>
                <div class="mb-4">
                    <label for="email" class="text-sm text-gray-600">Email</label>
                    <input type="email" name="email" id="email" value="{{ $user->email }}"
                        class="mt-1 py-2 px-3 border rounded-md w-full" readonly>
                </div>
                <div class="mb-4">
                    <label for="academic_status" class="text-sm text-gray-600">Academic Status</label>
                    <select name="academic_status" id="academic_status"
                        class="mt-1 py-2 px-3 border rounded-md w-full">
                        <option value="student" {{ $user->academic_status === 'student' ? 'selected' : '' }}>Student
                        </option>
                        <option value="teacher" {{ $user->academic_status === 'teacher' ? 'selected' : '' }}>Teacher
                        </option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="privacy" class="text-sm text-gray-600">Privacy</label>
                    <select name="privacy" id="privacy" class="mt-1 py-2 px-3 border rounded-md w-full">
                        <option value="public" {{ $user->is_private ? '' : 'selected' }}>Public</option>
                        <option value="private" {{ $user->is_private ? 'selected' : '' }}>Private</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="password" class="text-sm text-gray-600">Password</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 py-2 px-3 border rounded-md w-full">
                </div>
                <div class="">
                    <label for="password_confirmation" class="text-sm text-gray-600">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="mt-1 py-2 px-3 border rounded-md w-full">
                </div>
                <div id="password-confirmation-error" class="text-red-700 text-sm mb-4 "></div>
                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
