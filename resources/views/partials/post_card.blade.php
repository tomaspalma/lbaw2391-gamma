<head>
    @vite('resources/css/app.css')
</head>

<article class="post-card border border-black rounded-md my-4 p-2 cursor-pointer">
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
    <div class="post-action-bar">
        <button>{{$post->reactions_count}}</button>
        <button>
        </button>
        <button>
        </button>
    </div>
</article>
