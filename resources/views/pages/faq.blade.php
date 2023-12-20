@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/faq.js'])
    <title>{{ config('app.name', 'Laravel') }} | FAQ</title>

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Faq",
    'url' => $url,
    'image' => $logo
    ])

</head>

@include('partials.navbar')

<main class="mx-auto px-4 mt-16">
    <h1 class="text-2xl font-bold mb-8">Frequently Asked Questions</h1>

    <ul class="divide-y divide-gray-200">
        <li class="py-4">
            <h2 class="text-lg font-medium cursor-pointer flex flex-row justify-between items-center">
                What is this website about?
                <button class="faq-dropdown-arrow"><i class="fa-solid fa-angle-down arrow"></i></button>
            </h2>
            <p class="hidden elementToShow mt-2">This website is an academic social network designed to foster collaborative learning. It provides a platform for students to explore new subjects and assist each other in their academic pursuits.</p>
        </li>
        <li class="py-4">
            <h2 class="text-lg font-medium cursor-pointer flex flex-row justify-between items-center">
                How can I join the network?
                <button class="faq-dropdown-arrow"><i class="fa-solid fa-angle-down arrow"></i></button>
            </h2>
            <p class="hidden elementToShow mt-2">Joining our network is simple. Register an account, create a profile showcasing your academic status, university, and photos. Once set up, you can send friend requests, join academic groups, and share your learning experiences through posts.</p>
        </li>
        <li class="py-4">
            <h2 class="text-lg font-medium cursor-pointer flex flex-row justify-between items-center">
                Can I share my own educational content?
                <button class="faq-dropdown-arrow"><i class="fa-solid fa-angle-down arrow"></i></button>
            </h2>
            <p class="hidden elementToShow mt-2">Absolutely! We encourage users to actively participate in various groups and share posts about their favorite subjects, fostering a vibrant learning community.</p>
        </li>
        <li class="py-4">
            <h2 class="text-lg font-medium cursor-pointer flex flex-row justify-between items-center">
                How can I connect with other learners or educators?
                <button class="faq-dropdown-arrow"><i class="fa-solid fa-angle-down arrow"></i></button>
            </h2>
            <p class="hidden elementToShow mt-2">Connecting with other users is easy. Send friend requests to start interacting. Once connected, you can view, react to, and comment on their posts. Joining groups of common interest is another great way to connect.</p>
        </li>
        <li class="py-4">
            <h2 class="text-lg font-medium cursor-pointer flex flex-row justify-between items-center">
                Is there a way to give or receive feedback on the platform?
                <button class="faq-dropdown-arrow"><i class="fa-solid fa-angle-down arrow"></i></button>
            </h2>
            <p class="hidden elementToShow mt-2">Yes, our platform encourages active feedback. Users can comment on posts, providing valuable insights and constructive criticism, fostering a continuous learning environment.</p>
        </li>
    </ul>    
</main>

@include('partials.footer')
