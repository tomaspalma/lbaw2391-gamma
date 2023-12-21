@extends('layouts.head')

<head>
    @vite(['resources/css/app.css'])
    <title>{{ config('app.name', 'Laravel') }} | Main Features</title>

    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
    @endphp

    @include('partials.head.ogtags', [
    'title' => "Gamma | Main Features",
    'url' => $url,
    'image' => $logo
    ])

</head>

@include('partials.navbar')

<main class="mx-auto px-4 mt-8">
    <h1 class="text-2xl font-bold mb-4">Main Features</h1>

    <ul class="ml-5">
        <li class="mb-4">
            <h2 class="text-lg font-medium">Create Post</h2>
            <p>Users can create and share posts on their profile or in groups. This feature allows users to share their thoughts, ideas, or educational content with others. Users can also create polls with multiple options for other users to vote on.</p>
        </li>
        <li class="mb-4">
            <h2 class="text-lg font-medium">Comment on Posts</h2>
            <p>Users can comment on posts to share their thoughts or ask questions. This feature fosters discussion and helps users engage with each other.</p>
        </li>
        <li class="mb-4">
            <h2 class="text-lg font-medium">React to Posts and Comments</h2>
            <p>Users can react to posts and comments they find interesting or insightful with multiple reactions and emojis. This feature allows users to express their feelings about a post and engage with the content.</p>
        </li>
        <li class="mb-4">
            <h2 class="text-lg font-medium">Add Friends</h2>
            <p>Users can send friend requests to other users. Once accepted, users can see their friends' private posts and interact with them.</p>
        </li>
        <li class="mb-4">
            <h2 class="text-lg font-medium">Create and Join Groups</h2>
            <p>Users can create and join groups that align with their interests or academic fields. In these groups, users can share and discuss relevant content.</p>
        </li>
        <li class="mb-4">
            <h2 class="text-lg font-medium">Search for Users, Posts and Groups</h2>
            <p>Users can search for other users, posts and groups by name or content. This feature allows users to easily find what they are looking for, without needing to jump through many hoops.</p>
        </li>
        <li class="mb-4">
            <h2 class="text-lg font-medium">Change Visibility</h2> 
            <p>Users can change the visibility of their profile, posts and groups. This feature allows users to control who can see their content.</p>
        </li>
    </ul>    
</main>

@include('partials.footer')
