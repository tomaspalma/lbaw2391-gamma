<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<div id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-search-results" class="{{$previewMenuPosAbs ? 'absolute' : ''}} {{ $hidden ? 'hidden' : ''}} md-hidden mt-10 border {{$previewMenuWidth}} 
    bg-white rounded-4 p-4 {{ $previewMenuShadow ? 'shadow-xl' : ''}}">
    <ul id="{{$previewMenuName}}-preview-results" class="center justify-center flex border border-black rounded shadow my-4 cursor-pointer">
        <li data-id="selected" id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-users-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center {{($toggled === 'users' || !$toggled) ? 'border-t-4 border-black' : '' }}">
            @if($query !== null)
            <a href="/search/{{$query}}?toggled=users">Users</a>
            @else
            Users
            @endif
        </li>
        <li id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-posts-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center {{$toggled === 'posts' ? 'border-t-4 border-black' : ''}}">
            @if($query !== null)
            <a href="/search/{{$query}}?toggled=posts">Posts</a>
            @else
            Posts
            @endif
        </li>
        <li id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-groups-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center {{$toggled === 'groups' ? 'border-t-4 border-black' : ''}}">
            @if($query !== null)
            <a href="/search/{{$query}}?toggled=groups">Groups</a>
            @else
            Groups
            @endif
        </li>
    </ul>
    <div class="{{$previewMenuName !== 'main-search-preview' ? 'max-h-96 overflow-auto' : ''}}" id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-search-preview-content">
        @if (count($entities) === 0)
            <p class="text-center">No {{request('toggled')}} found.</p>
        @endif

        @foreach ($entities as $entity)
        @if($toggled === 'users')
        @include('partials.user_card', ['user'=> $entity, 'adminView' => false])
        @elseif($toggled === 'posts')
        @include('partials.post_card', ['post'=> $entity])
        @elseif($toggled === 'groups')

        @endif
        @endforeach
    </div>
</div>
