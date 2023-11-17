@extends('layouts.head')

@include('partials.navbar')

<head>
    @vite(['resources/css/app.css', 'resources/js/auth/register.js'])
</head>

<main class="center">

    @include('partials.auth.register_form')
</main>
