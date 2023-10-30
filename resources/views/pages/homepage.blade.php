@extends('layouts.head')

<head>
    @vite('resources/css/app.css')
</head>

<div class="flex justify-center space-x-4">
    <button class="bg-black hover:bg-white text-white hover:text-black hover:border hover:border-black font-bold py-2 px-2 rounded">
        <a href="/feed">Popular</a>
    </button>
    <button class="bg-black hover:bg-white text-white hover:text-black hover:border hover:border-black font-bold py-2 px-2 rounded">
        <a href="/feed/personal">Personal</a>
    </button>
</div>

@if($feed === "popular")
    <h1>Popular</h1>
@elseif($feed === "personal")
    <h1>Personal</h1>
@endif
