@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/auth/seePassword.js'])

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<div class="flex justify-center">
    <form method="POST" class="border-2 border-gray-500 p-4 w-96 max-w-screen-md justify-center mt-4" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">E-mail</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('email') }}
            </span>
            @endif
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="password" type="password" name="password" required>
            
            {{-- <i class="fas fa-eye-slash" id="togglePassword" style="margin-top: -29px; margin-left: 310px;"></i> --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-5 h-5 cursor-pointer" id="togglePassword" style="margin-top: -29px; margin-left: 310px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>
              
            @if ($errors->has('password'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('password') }}
            </span>
            @endif
        </div>

        <div class="mb-4">
            <label for="remember" class="inline-block text-sm text-gray-600">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
        </div>

        <div class="flex items-center">
            <button class="bg-blue-500 text-white py-2 px-4 rounded" type="submit">
                Login
            </button>
            <a class="ml-2 text-blue-500" href="{{ route('register') }}">Register</a>
        </div>

        @if (session('success'))
        <p class="text-green-500 text-sm mt-2">
            {{ session('success') }}
        </p>
        @endif
    </form>
</div>
