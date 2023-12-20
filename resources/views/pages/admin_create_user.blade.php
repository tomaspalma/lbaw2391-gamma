@extends('layouts.head')

@include('partials.navbar')

<head>
    <title>{{ config('app.name', 'Laravel') }} | Admin create user</title>
    @vite(['resources/css/app.css', 'resources/js/auth/register.js'])

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Admin Create User",
    'url' => $url,
    'image' => $logo
    ])

</head>

<main class="center">

    @include('partials.auth.register_form', ['admin_page_version' => true])
</main>

@include('partials.footer')
