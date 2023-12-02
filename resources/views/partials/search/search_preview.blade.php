<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<div id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-search-results" class="{{$previewMenuPosAbs ? 'absolute' : ''}} {{ $hidden ? 'hidden' : ''}} md-hidden mt-10 border z-50 {{$previewMenuWidth}} 
    bg-white rounded-4 p-4 {{ $previewMenuShadow ? 'shadow-xl' : ''}}">
    <ul id="{{$previewMenuName}}-preview-results" class="center justify-center flex border border-black rounded shadow my-4 cursor-pointer">
        <li data-id="selected" id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-users-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center {{($toggled === 'users' || !$toggled) ? 'border-t-4 border-black' : '' }}">
            Users
        </li>
        <li id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-posts-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center {{$toggled === 'posts' ? 'border-t-4 border-black' : ''}}">
            Posts
        </li>
        <li id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-groups-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center {{$toggled === 'groups' ? 'border-t-4 border-black' : ''}}">
            Groups
        </li>
    </ul>
    <div id="{{$isMobile ? 'mobile-' : ''}}{{$previewMenuName}}-search-preview-content">

    </div>
</div>
