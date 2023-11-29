@extends('layouts.head')

@include('partials.navbar')

<h1 class="text-center title">You were banned from Gamma</h1>
<div class="flex justify-center">
    <form method="POST" class="border-2 border-gray-500 p-4 w-96 max-w-screen-md justify-center mt-4" action="{{route('appban_appeal_form.show', ['username' => request()->route('username')])}}">
        @csrf
        <div class="mb-4">
            <label for="appeal" class="block text-sm font-medium text-gray-600">Appeal</label>
            <textarea required name="appeal" id="appeal" rows="6" class="mt-2 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Give a reason why an admin should unban you from the website"></textarea>
            @if ($errors->has('appeal'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('appeal') }}
            </span>
            @endif
        </div>
        <div class="flex items-center">
            <button class="w-full bg-black hover:bg-gray-600 transition-colors text-white py-2 px-4 rounded" type="submit">
                Submit
            </button>
        </div>
    </form>
</div>
