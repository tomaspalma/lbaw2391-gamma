<head>
    @vite('resources/css/app.css')
</head>

<article class="post-card border border-black rounded-md my-4 p-2">
    <div class="flex align-middle space-x-4">
        <img src="{{ $post->authors->image ?? 'hello'}}" class="rounded-full w-10 h-10">
        <a href="">{{ $post->authors->username ?? 'hello' }}</a>
    </div>
    <header class="my-4">
        <h1 class="text-2xl">
            {{ $post->title }}
        </h1>
    </header>
    <p class="my-4">
        {{ $post->content }}
    </p>
    <div class="post-action-bar">
        <button>3</button>
        <button>comment</button>
        <button>copy post link</button>
        <span><time>Date</time></span>
    </div>
</article>
