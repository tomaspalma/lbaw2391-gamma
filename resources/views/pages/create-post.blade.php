@extends('layouts.head')

<head>
    @vite('resources/css/app.css')

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center">
    <div class="create-post-container">
        <h2>Create a New Post</h2>

        <form action="{{ route('post.create') }}" method="post">
            @csrf

            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="content">Content:</label>
            <textarea name="content" id="content" rows="4" required></textarea>

            <label for="is_private">Privacy:</label>
            <select name="is_private" id="is_private" required>
                <option value="0">Public</option>
                <option value="1">Private</option>
            </select>
            
 
            <button type="submit">Create Post</button>
        </form>
    </div>
</main>
