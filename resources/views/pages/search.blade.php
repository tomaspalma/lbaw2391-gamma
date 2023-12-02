@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search/main_search/scroll.js'])
    <meta name="search" content="{{ $query ? $query : '' }}">
</head>

@include('partials.navbar')

<main class="center">
    @include('partials.search.search_preview', ['previewMenuName' => 'main-search-preview'])
</main>
