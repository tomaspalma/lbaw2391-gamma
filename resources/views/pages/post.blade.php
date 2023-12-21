@extends('layouts.head')

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js' , 'resources/js/post/delete.js', 'resources/js/comment/add.js' , 'resources/js/comment/delete.js', 'resources/js/post/scroll.js', 'resources/js/post/copy_link.js', 'resources/js/post/poll.js'])

    <title>{{ config('app.name', 'Laravel') }} | Post {{$post->title}}</title>
    
    @php
        $url = Request::url();
        $logo = config('app.url', $url) . "/public/logo.png";
        $title = "Gamma | Post " . $post->title;
    @endphp

    @include('partials.head.ogtags', [
    'title' => $title,
    'url' => $url,
    'image' => $logo
    ])


    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@include('partials.navbar')

<main class="center md:mb-12">
    <article id="post-article" data-selected-option="{{ Auth::user() ? (Auth::user()->vote_on_post_poll($post)[0]->name ?? '') : '' }}" 
        data-entity="post" data-entity-id="{{$post->id}}" post-id="{{$post->id}}" 
        class="border border-black rounded-md p-8 my-8 max-w-3xl mx-auto shadow-md">
        <header class="flex flex-col space-y-2 md:space-y-0 md:flex-row justify-between items-center">
            <h1 class="md:text-left text-center text-2xl md:text-4xl font-bold w-full break-words">
                {{ $post->title }}
                <button data-entity-id="{{$post->id}}" class="mb-1 p-2 text-base rounded-md hover:bg-black hover:text-white transition-colors post-copy-link-btn">
                    <i class="copy-link-icon"></i>
                </button>
            </h1>
            <span class="text-gray-600">
                <time>{{ $post->format_date() }}</time>
            </span>
        </header>

        <div class="flex space-x-4 mt-4">
            <img src="{{ $post->owner->getProfileImage('small') ?? 'hello' }}" class="rounded-full w-10 h-10" alt="{{ $post->owner->username }}'s Profile Image">

            <a class="text-lg text-gray-600 hover:underline" href="{{ route('profile',['username' => $post->owner->username]) }}">
                {{ $post->owner->username }}
                @auth
                    @if(Auth::user()->username === $post->owner->username)
                        (<span class="italic">you</span>)
                    @endif
                @endauth
            </a>
            @if($post->group)
            <a class="text-lg text-gray-600 hover:underline w-full break-words" href="{{route('groupPosts', ['id' => $post->group_id])}}">@ {{ $post->group->name }}</a>
            @endif
        </div>

        <div class="mt-6 break-words max-w-full">
            @php
                $pattern = '/\[\[(.*?)\]\]/';
                $parts = preg_split($pattern, $post->content, -1, PREG_SPLIT_DELIM_CAPTURE)
            @endphp
            @foreach ($parts as $part)
                @if (!empty($part) && $part[0] === "{")
                    @php
                        $json = json_decode($part, true);
                    @endphp
                    <a target="_blank" class="text-blue-700" href="{{'/users/' . $json['username']}}">
                        {{ $json['username'] }}
                    </a>
                @else
                    {{$part}}
                @endif
            @endforeach
        </div>

        @if($post->poll !== null)
            @include('partials.post_poll', ['pollOptions' => $pollOptions])
        @endif

        @if($post->attachment)
        <div>
            <img src="{{ $post->getAttachment() }}" alt="Attachment" class="center my-8 w-2/3 rounded-md shadow-md">
        </div>
        @endif

        <div class="post-action-bar mt-4 flex justify-between items-center">
            @php
            $f = function($user, $post) {
            return $user->post_reaction($post);
            }
            @endphp
            @include('partials.reactions', ['entity' => $post, 'entity_function' => $f, 'entity_name' => 'post'])
        </div>

        @can('update', $post)
        <div class="flex justify-between items-center mt-4">
            <a href="{{ route('post.update', $post->id) }}" class="form-button hover:no-underline py-2 px-4 rounded-md">Edit Post</a>
            <button type="submit" class="delete-post-button form-button-red py-2 px-4 rounded-md">Delete Post</button>
        </div>
        @elsecan('delete', $post)
        <div class="flex justify-end items-center">
            <button type="submit" class="delete-post-button form-button-red py-2 px-4 rounded-md">Delete Post</button>
        </div>
        @endcan
    </article>

    <section class="border border-black p-4 my-4 max-w-3xl mx-auto rounded-md shadow-md">
        <h3 class="text-2xl font-bold mb-4">Comments</h3>

        @can('create', App\Models\Comment::class)
        <form id="comment-form" class="flex flex-col space-y-4">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <textarea name="content" class="border border-gray-300 rounded-md p-2 resize-y" placeholder="Write a comment..."></textarea>
            <button type="button" id="comment-button" class="form-button py-2 px-4 rounded-md">Comment</button>
        </form>
        @endcan

        <div id="comments" class="mt-4">
            @forelse($comments as $comment)
            @include('partials.comment_card', ['comment'=> $comment])
            @empty
            <p id="no-comment">No comments yet.</p>
            @endforelse
        </div>

    </section>
</main>

@include('partials.snackbar')

@include('partials.footer')
