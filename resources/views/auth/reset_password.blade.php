@extends('layouts.head')

@include('partials.navbar')

<h1 class="text-center title">Reset Password</h1>
<div class="flex justify-center">
    <form method="POST" class="w-96 max-w-screen-md form-card mt-4" action="{{ route('password.update', ['token' => request('token')]) }}">
        @csrf

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
            <label for="email" class="block text-sm font-medium text-gray-600">Password</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="password" type="password" name="password" required autofocus>
            @if ($errors->has('email'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('email') }}
            </span>
            @endif
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">Confirm Password</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="confirm-password" type="password" name="password_confirmation" required autofocus>
            @if ($errors->has('email'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('email') }}
            </span>
            @endif
        </div>
        <input class="mt-1 p-2 w-full border focus:ring-2" name="token" type="text" value="{{ request('token') }}" hidden>
        <div class="flex items-center">
            <button class="w-full form-button text-white py-2 px-4 rounded" type="submit">
                Submit
            </button>
        </div>
    </form>
</div>
