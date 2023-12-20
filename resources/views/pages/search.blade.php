@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search/main_search/scroll.js'])

    <title>{{ config('app.name', 'Laravel') }} | Main search page</title>

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Main search page",
    'url' => $url,
    'image' => $logo
    ])


    <meta name="search" content="{{ $query ? $query : '' }}">
</head>

@include('partials.navbar')

<main class="center">
    @include('partials.search.search_preview', ['previewMenuName' => 'main-search-preview'])
</main>

@include('partials.snackbar')
