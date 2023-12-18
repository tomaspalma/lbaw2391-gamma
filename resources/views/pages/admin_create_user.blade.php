@extends('layouts.head')

@include('partials.navbar')

<head>
    <title>{{ config('app.name', 'Laravel') }} | Admin create user</title>
    @vite(['resources/css/app.css', 'resources/js/auth/register.js'])
</head>

<main class="center">

    @include('partials.auth.register_form', ['admin_page_version' => true])
</main>

@include('partials.footer')