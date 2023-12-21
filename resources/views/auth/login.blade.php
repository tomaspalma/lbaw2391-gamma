@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/auth/seePassword.js'])

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<h1 class="text-xl text-center">Login</h1>
<div class="flex justify-center">
    <form method="POST" class="form-card w-96 max-w-screen-md mt-4" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="mb-4">
            <label for="identifier" class="block text-sm font-medium text-gray-600">
                E-mail / Username
                <span class="required-input">*</span>
            </label>
            <input placeholder="Email / username" class="rounded-md mt-1 p-2 w-full border focus:ring-2" id="identifier" name="identifier" value="{{ old('identifier') }}" required autofocus>
            @if ($errors->has('identifier'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('identifier') }}
            </span>
            @endif
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600">
                Password
                <span class="required-input">*</span>
            </label>
            <div class="relative">
                <input placeholder="Password" class="mt-1 p-2 pr-10 w-full border rounded-md focus:ring-2" id="password" type="password" name="password" required>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <i class="fas fa-eye-slash cursor-pointer" id="togglePassword"></i>
                </div>
            </div>

            @if ($errors->has('password'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('password') }}
            </span>
            @endif

            <div class="mt-2">
                <a href="{{ route('password.request') }}" class="mt-4 blue-link-color">Reset password</a>
            </div>
        </div>

        <div class="mb-4">
            <label for="remember" class="inline-block text-sm text-gray-600">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
        </div>

        <div class="mt-2 flex justify-between items-center">
            <button class="form-button py-2 px-4 rounded" type="submit">
                Login
            </button>
            <a class="ml-2 blue-link-color" href="{{ route('register') }}">Register</a>
        </div>

        @if (session('success'))
        <p class="success text-sm mt-2">
            {{ session('success') }}
        </p>
        @endif
    </form>
</div>
