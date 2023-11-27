@extends('layouts.head')

@include('partials.navbar')

<h1 class="text-center title">Reset Password</h1>
<div class="flex justify-center">
    <form method="POST" class="border-2 border-gray-500 p-4 w-96 max-w-screen-md justify-center mt-4" action="{{ route('send_reset_password_request') }}">
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

        @if(session('status'))
        <p class="text-center success">Reset link sent with success</p>
        @endif
        <div class="flex items-center">
            <button class="mt-2 w-full bg-blue-500 text-white py-2 px-4 rounded" type="submit">
                Submit
            </button>
        </div>
    </form>
</div>
