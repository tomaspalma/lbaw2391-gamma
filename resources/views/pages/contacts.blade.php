@extends('layouts.head')

<head>
    @vite(['resources/css/app.css'])
    <title>{{ config('app.name', 'Laravel') }} | Contact Us</title>

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Contacts",
    'url' => $url,
    'image' => $logo
    ])

</head>

@include('partials.navbar')

<main class="mx-auto px-4 mt-16">
    <h1 class="text-2xl font-bold mb-4">Contact Us</h1>

    <p class="mb-4">
        We'd love to hear from you! Whether you have a question about our platform, need assistance, or just want to give us feedback, feel free to get in touch.
    </p>

    <h2 class="text-lg font-medium mb-2">Email</h2>
    <p class="mb-4">lbaw2391@gmail.com</p>

    <h2 class="text-lg font-medium mb-2">Address</h2>
    <p class="mb-4">Rua Dr. Roberto Frias, 4200-465 Porto</p>
</main>

@include('partials.footer')
