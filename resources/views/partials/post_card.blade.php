<head>
    @vite(['resources/js/post/copy_link.js'])
</head>

<article data-entity="post" data-entity-id="{{$post->id}}" class="shadow-md post-card border border-black rounded-md my-4 p-6 cursor-pointer">
    <div class="flex align-middle justify-between space-x-4">
        <div class="flex space-x-4">
            <img src="{{ $post->owner->getProfileImage() ?? 'hello'}}" class="rounded-full w-10 h-10" alt="{{ $post->owner->username }}'s Profile Image">
            <a class="hover:underline" href="{{ route('profile',['username' => $post->owner->username]) }}">
                {{ $post->owner->username }} 
                @auth
                    @if(Auth::user()->username === $post->owner->username)
                        (<span class="text-sm italic">you</span>)
                    @endif
                @endauth
            </a>
            @if($post->group)
            <a class="hover:underline" href="{{route('groupPosts', ['id' => $post->group_id])}}">@ {{ $post->group->name }}</a>
            @endif
        </div>
        <span>
            <time>{{ $post->format_date() }}</time>
        </span>
    </div>
    <header class="my-4">
        <h1 class="text-2xl">
            <a class="hover:underline w-full break-words" href="{{ route('post.show', $post->id) }}">{{ $post->title }}</a>
            <button data-entity-id="{{$post->id}}" class="mb-1 p-2 text-base rounded-md hover:bg-black hover:text-white transition-colors post-copy-link-btn">
                <i class="copy-link-icon"></i>
            </button>
        </h1>
    </header>
    <p class="my-4 w-full break-words">
        {{ strlen($post->content) <= 400 ? $post->content : substr($post->content, 0, 400) .'...' }}
        @if(strlen($post->content) > 400)
        <a class="text-blue-700" href="{{ route('post.show', ['id' => $post->id]) }}">
            View more
        </a>
        @endif
    </p>
    @if($preview === false)
    @php
    $f = function($user, $post) {
    return $user->post_reaction($post);
    }
    @endphp
    @include('partials.reactions', ['entity' => $post, 'entity_function' => $f, 'entity_name' => 'post'])
    @endif
</article>
