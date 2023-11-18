@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/auth/login.js'])

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/38229b6c34.js" crossorigin="anonymous"></script>
</head>

@include('partials.navbar')

<div class="flex items-center justify-content: center min-h-screen">
    <form method="POST" class="border-2 border-gray-500 p-4 w-96 max-w-screen-md justify-self: center" action="{{ route('login') }}">
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
            
            <i class="fas fa-eye-slash" id="togglePassword" style="margin-top: -29px; margin-left: 310px;"></i>
            
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
