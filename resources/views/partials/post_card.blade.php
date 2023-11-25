<head>
    @vite(['resources/css/app.css', 'resources/js/post/reactions.js'])
</head>

<article data-post-id="{{$post->id}}" class="post-card border border-black rounded-md my-4 p-2 cursor-pointer">
    <div class="flex align-middle justify-between space-x-4">
        <div class="flex space-x-4">
            <img src="{{ $post->owner->getProfileImage() ?? 'hello'}}" class="rounded-full w-10 h-10">
            <a class="hover:underline" href="{{ route('profile',['username' => $post->owner->username]) }}">{{ $post->owner->username ?? 'hello' }}</a>
            @if($post->group)
            <a class="hover:underline">@ {{ $post->group->name }}</a>
            @endif
        </div>
        <span>
            <time>{{ $post->format_date() }}</time>
        </span>
    </div>
    <header class="my-4">
        <h1 class="text-2xl">
            <a class="hover:underline" href="{{ route('post.show', $post->id) }}">{{ $post->title }}</a>
        </h1>
    </header>
    <p class="my-4">
        {{ $post->content }}
    </p>
    <div class="flex flex-col divide-y-2">
        @auth
            @include('partials.reactions', ['entity' => $post])
        @endauth
        <div class="flex flex-row space-x-2 items-center">
            @foreach ($post->reactionsMap() as $icon => $metadata)
                <div>
                    <i class="fa-solid {{$icon}} {{$metadata[1]}}"></i>
                    {{$metadata[0]}}
                </div>
            @endforeach
        </div>
    </div>
</article>
