<head>
    @vite('resources/css/app.css')
</head>

<article class="post-card border border-black rounded-md my-4 p-2 cursor-pointer">
    <div class="flex align-middle justify-between space-x-4">
        <div class="flex space-x-4">
            <img src="{{ $post->authors->image ?? 'hello'}}" class="rounded-full w-10 h-10">
            <a class="hover:underline" href="">{{ $post->authors->username ?? 'hello' }}</a>
        </div>
        <span>
            <time>{{ $post->date }}</time>
        </span>
    </div>
    <header class="my-4">
        <h1 class="text-2xl">
            <a class="hover:underline">{{ $post->title }}</a>
        </h1>
    </header>
    <p class="my-4">
        {{ $post->content }}
    </p>
    <div class="post-action-bar">
        <button>3</button>
        <button>
        </button>
        <button>
        </button>
    </div>
</article>
