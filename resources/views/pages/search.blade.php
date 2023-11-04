@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/search/search_input_preview.js', 'resources/js/search/main_search_preview.js'])

    <meta name="search" content="{{ $query ? $query : '' }}">
</head>

@include('partials.navbar')

<main class="center">
    @include('partials.search.search_preview')
</main>
