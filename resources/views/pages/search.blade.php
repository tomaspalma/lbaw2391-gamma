@extends('layouts.head')

<head>
    @vite('resources/css/app.css')
    
    <meta name="search" content="{{ $query ? $query : '' }}">
</head>

@include('partials.navbar')

<main class="center">
    <ul class="tab-container w-full flex border border-black rounded shadow">
        <li class="flex w-1/2 p-2 justify-center">
            <a href="/feed" class="hover:underline">Users</a>
        </li>
        <li class="flex w-1/2 p-2 justify-center">
            <a href="/feed/personal" class="hover:underline">Posts</a>
        </li>
        <li class="flex w-1/2 p-2 justify-center">
            <a href="/feed" class="hover:underline">Groups</a>
        </li>

    </ul>
</main>
