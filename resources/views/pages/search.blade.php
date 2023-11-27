@extends('layouts.head')

<head>

    <meta name="search" content="{{ $query ? $query : '' }}">
</head>

@include('partials.navbar')

<main class="center">
    @include('partials.search.search_preview', ['previewMenuName' => 'main-search-preview'])
</main>
