@extends('layouts.head')

<head>
    @vite(['resources/css/app.css'])
    <title>{{ config('app.name', 'Laravel') }} | About Us</title>

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | About Us",
    'url' => $url,
    'image' => $logo
    ])
</head>

@include('partials.navbar')

<main class="mx-auto px-4 mt-16">
    <h1 class="text-2xl font-bold mb-8">About Us</h1>

    <p class="mb-4">
        We are an academic social network designed to foster collaborative learning. Our platform provides a space for students to explore new subjects and assist each other in their academic pursuits.
    </p>

    <p class="mb-4">
        Our mission is to make learning accessible and enjoyable for everyone. We believe in the power of community and the importance of sharing knowledge. 
    </p>

    <p>
        Whether you're a student looking to expand your horizons or an educator seeking to connect with learners, we welcome you to our network. Together, we can make learning an exciting journey.
    </p>
</main>

@include('partials.footer')
