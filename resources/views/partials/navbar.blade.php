<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search/search_input_preview.js', 'resources/js/components/navbar_mobile_menu.js'])
</head>

@auth
@if(!Auth::user()->has_verified_email() && !request()->session()->get('reset_new_validation'))
<div class="email-verification-link">
    You still haven't confirmed your email.
    <form class="inline" method="POST" action="{{route('verification.send')}}">
        @csrf
        <button type="submit" class="hover:underline">Click here to resend</button>
    </form>
</div>
@endif
@endauth
<nav class="bg-white border-black border-b @if(!isset($no_margin)) mb-5 @endif p-4 shadow-md">
    <div class="max-w-screen-xl flex flex-col md:flex-row justify-around mx-auto">
        <div class="flex w-full md:w-1/3 self-center justify-center md:justify-normal">
            <a href="/" class="text-2xl font-bold hover:underline">
                <svg class="w-36 h-fit" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev/svgjs" width="2000" height="464" viewBox="0 0 2000 464"><g transform="matrix(1,0,0,1,-1.2121212121212466,0.3057212185776734)"><svg viewBox="0 0 396 92" data-background-color="#ffffff" preserveAspectRatio="xMidYMid meet" height="464" width="2000" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="tight-bounds" transform="matrix(1,0,0,1,0.2400000000000091,-0.060617138166264795)"><svg viewBox="0 0 395.52 92.12123427633256" height="92.12123427633256" width="395.52"><g><svg viewBox="0 0 485.8839320002116 113.16805099842519" height="92.12123427633256" width="395.52"><g transform="matrix(1,0,0,1,90.36393200021168,13.357115262992124)"><svg viewBox="0 0 395.5199999999999 86.45382047244094" height="86.45382047244094" width="395.5199999999999"><g id="textblocktransform"><svg viewBox="0 0 395.5199999999999 86.45382047244094" height="86.45382047244094" width="395.5199999999999" id="textblock"><g><svg viewBox="0 0 395.5199999999999 86.45382047244094" height="86.45382047244094" width="395.5199999999999"><g transform="matrix(1,0,0,1,0,0)"><svg width="395.5199999999999" viewBox="2.4 -34.3 158.73999999999998 34.699999999999996" height="86.45382047244094" data-palette-color="#000000"><path d="M30.1-2.8L30.15-2.65Q26.1 0.4 19.7 0.4L19.7 0.4Q11.9 0.4 7.15-4.23 2.4-8.85 2.4-16.9L2.4-16.9Q2.4-24.55 7.13-29.43 11.85-34.3 19.05-34.3L19.05-34.3Q25-34.3 28.9-32L28.9-32 28.9-27.65Q24.8-30.35 19.4-30.35L19.4-30.35Q13.75-30.35 10.2-26.68 6.65-23 6.65-16.9L6.65-16.9Q6.65-10.65 10.15-7.08 13.65-3.5 19.8-3.5L19.8-3.5Q23.2-3.5 26.1-4.85L26.1-4.85 26.1-15.35 18.7-15.35 18.7-18.9 30.15-18.9 30.15-2.8 30.1-2.8ZM44.9-3.05L44.9-3.05Q47.6-3.05 49.2-4.58 50.8-6.1 50.8-8.45L50.8-8.45 50.8-11.7 46.4-11.7Q43.2-11.7 41.6-10.45 40-9.2 40-7.3 40-5.4 41.25-4.23 42.5-3.05 44.9-3.05ZM50.85 0L50.85-3Q48.9 0.25 43.75 0.25L43.75 0.25Q40.2 0.25 38.02-1.85 35.85-3.95 35.85-7.15L35.85-7.15Q35.85-10.8 38.62-12.83 41.4-14.85 46.2-14.85L46.2-14.85 50.8-14.85 50.8-16.15Q50.8-19.15 49.45-20.68 48.1-22.2 44.9-22.2L44.9-22.2Q40.85-22.2 37.7-20.05L37.7-20.05 37.7-24Q40.55-25.75 45.2-25.75L45.2-25.75Q54.75-25.75 54.75-16.05L54.75-16.05 54.75 0 50.85 0ZM95.65-17.4L95.65 0 91.7 0 91.7-16.4Q91.7-22.15 86.84-22.15L86.84-22.15Q84.45-22.15 82.55-19.98 80.65-17.8 80.65-13.85L80.65-13.85 80.65 0 76.7 0 76.7-16.4Q76.7-22.15 71.84-22.15L71.84-22.15Q69.45-22.15 67.55-19.98 65.65-17.8 65.65-13.85L65.65-13.85 65.65 0 61.75 0 61.75-25.35 65.65-25.35 65.65-21.25Q67.75-25.7 72.8-25.7L72.8-25.7Q78.65-25.7 80.2-20.75L80.2-20.75Q81.3-23.15 83.27-24.43 85.25-25.7 87.7-25.7L87.7-25.7Q91.65-25.7 93.65-23.45 95.65-21.2 95.65-17.4L95.65-17.4ZM136.54-17.4L136.54 0 132.59 0 132.59-16.4Q132.59-22.15 127.74-22.15L127.74-22.15Q125.34-22.15 123.44-19.98 121.54-17.8 121.54-13.85L121.54-13.85 121.54 0 117.59 0 117.59-16.4Q117.59-22.15 112.74-22.15L112.74-22.15Q110.34-22.15 108.44-19.98 106.54-17.8 106.54-13.85L106.54-13.85 106.54 0 102.64 0 102.64-25.35 106.54-25.35 106.54-21.25Q108.64-25.7 113.69-25.7L113.69-25.7Q119.54-25.7 121.09-20.75L121.09-20.75Q122.19-23.15 124.17-24.43 126.14-25.7 128.59-25.7L128.59-25.7Q132.54-25.7 134.54-23.45 136.54-21.2 136.54-17.4L136.54-17.4ZM151.29-3.05L151.29-3.05Q153.99-3.05 155.59-4.58 157.19-6.1 157.19-8.45L157.19-8.45 157.19-11.7 152.79-11.7Q149.59-11.7 147.99-10.45 146.39-9.2 146.39-7.3 146.39-5.4 147.64-4.23 148.89-3.05 151.29-3.05ZM157.24 0L157.24-3Q155.29 0.25 150.14 0.25L150.14 0.25Q146.59 0.25 144.42-1.85 142.24-3.95 142.24-7.15L142.24-7.15Q142.24-10.8 145.02-12.83 147.79-14.85 152.59-14.85L152.59-14.85 157.19-14.85 157.19-16.15Q157.19-19.15 155.84-20.68 154.49-22.2 151.29-22.2L151.29-22.2Q147.24-22.2 144.09-20.05L144.09-20.05 144.09-24Q146.94-25.75 151.59-25.75L151.59-25.75Q161.14-25.75 161.14-16.05L161.14-16.05 161.14 0 157.24 0Z" opacity="1" transform="matrix(1,0,0,1,0,0)" fill="#000000" class="wordmark-text-0" data-fill-palette-color="primary" id="text-0"></path></svg></g></svg></g></svg></g></svg></g><g><svg viewBox="0 0 69.95425987835971 113.16805099842519" height="113.16805099842519" width="69.95425987835971"><g><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="30.570975056689345 19.461897075889475 38.92902494331065 62.97717805554813" enable-background="new 0 0 100 100" xml:space="preserve" height="113.16805099842519" width="69.95425987835971" class="icon-icon-0" data-fill-palette-color="accent" id="icon-0"><path fill="#000000" d="M43.9 22.8c-2.1-3.3-5.8-3.6-7.3-3.2-6.8 2-6 14.2-6 14.2h2c0 0 0.5-8.5 4.7-8.2 2.8 0.2 5.8 1.7 8 13.8 0.6 3.2 3.5 20 3.5 20-5.6 14.1-5.9 16.2-5.5 19.2 0.5 3.5 3.2 3.8 3.2 3.8s4.7 1.2 5-9.7c0.4-11.8-0.3-7 0-13.8l18-38.8h-7.7l-11 33.7C50.8 53.8 48 29.1 43.9 22.8z" data-fill-palette-color="accent"></path></svg></g></svg></g></svg></g><defs></defs></svg><rect width="395.52" height="92.12123427633256" fill="none" stroke="none" visibility="hidden"></rect></g></svg></g></svg>
            </a>
        </div>
        <div class="flex flex-col items-center md:w-1/3 w-full justify-center">
            @if(Auth::user() === null || !Auth::user()->is_app_banned())
            <form id="mobile-search-form" class="relative md:hidden">
                <label for="search" class="sr-only">Search</label>
                <input name="search" type="text" id="mobile-search-trigger" class="mt-4 md:hidden block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
            </form>
            <form id="search-form" class="relative hidden md:block w-full mb-0">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    
                    <span class="sr-only">Search icon</span>
                </div>
                <label for="search" class="sr-only">Search</label>
                <input name="search" type="text" id="search-navbar" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
            </form>

            @include('partials.search.search_preview', [
            'hidden' => true,
            'previewMenuShadow' => false,
            'previewMenuWidth' => 'w-full',
            'previewMenuPosAbs' => false,
            'previewMenuName' => 'search-input',
            'toggled' => 'users',
            'isMobile' => true,
            'entities' => [],
            'query' => null,
            ])

            @include('partials.search.search_preview', [
            'hidden' => true,
            'previewMenuShadow' => true,
            'previewMenuWidth' => 'w-1/3',
            'previewMenuPosAbs' => true,
            'previewMenuName' => 'search-input',
            'toggled' => 'users',
            'isMobile' => false,
            'entities' => [],
            'query' => null
            ])
            @endif
        </div>
        <div class="items-center md:flex md:w-1/3 md:justify-end">
            <i id="hamburger-toggle" class="md:hidden block fa-solid fa-bars mt-4 cursor-pointer"></i>
            <ul id="navbar-menu" class="hidden md:flex flex flex-col justify-center align-middle items-center p-4 md:p-0 mt-4 font-medium bg-gray-50 md:flex-row md:space-x-2 md:mt-0 md:border-0 md:bg-white">
                @if(Auth::user() ? Auth::user()->is_admin() : 0)
                <li>
                    <a href="/admin/user" class="block py-2 pl-3 pr-4">Admin</a>
                </li>
                @endif
                @guest
                <li>
                    <a href="/login/" class="block py-2 pl-3 pr-4">Login</a>
                </li>
                <li>
                    <a href="/register/" class="block py-2 pl-3 pr-4">Register</a>
                </li>
                @endguest
                @auth
                <li>
                    <form id="logout-action" method="POST" action="{{ route('logout') }}" class="block py-2 pl-3 pr-4 mb-0">
                        @csrf
                        <button type="submit" class="hover:underline">Logout</button>
                    </form>
                </li>
                @endauth
                @auth
                @if(!Auth::user()->is_app_banned())
                <a href="/users/{{Auth::user()->username}}/friends" class="block py-2 pl-3 pr-4">
                    <div class="relative flex flex-row md:flex-col space-x-1 md:space-x-0">
                        <i class="fa-solid fa-user-group text-2xl max-md:hidden"></i>
                        <span class="md:hidden">Friends</span>
                        <span id="friend-request-counter" class="{{count(Auth::user()->received_pending_friend_requests()->get()) > 0 ? '' : 'hidden'}} text-xs md:absolute md:bottom-3 md:left-3 bg-red-500 text-white w-5 h-5 flex items-center justify-center rounded-full">
                            {{count(Auth::user()->received_pending_friend_requests()->get())}}
                        </span>
                    </div>
                </a>              
                <li>
                    @include('partials.notifications.notification_bell')
                </li>

                @auth
                    <li>
                        <a href="/groups" class="block py-2 pl-3 pr-4 text-xl">
                            <div class="relative flex flex-row md:flex-col md:space-x-0">
                                <i class="fa-sharp fa-solid fa-people-group text-2xl"></i>
                                <span id="group-request-counter" class="{{count(Auth::user()->groupRequests()) > 0 ? '' : 'hidden'}} text-xs md:absolute md:bottom-3 md:left-3 bg-red-500 text-white w-5 h-5 flex items-center justify-center rounded-full">
                                    {{count(Auth::user()->groupRequests())}}
                                </span>
                            </div>
                        </a>
                    </li>
                @endauth

                <li class="flex items-center space-x-0">
                    <a href="/users/{{Auth::user()->username}}" class="block py-2 pl-3 pr-1">
                        Profile
                    </a>
                </li>
                @endif
                @endauth
                <li class="md:hidden">
                    <a href="{{ route('about') }}" class="block py-2 pl-3 pr-4">About Us</a>
                </li>
                <li class="md:hidden">
                    <a href="{{ route('features') }}" class="block py-2 pl-3 pr-4">Main Features</a>
                </li>
                <li class="md:hidden">
                    <a href="{{ route('faq') }}" class="block py-2 pl-3 pr-4">FAQ</a>
                </li>
                <li class="md:hidden">
                    <a href="{{ route('contacts') }}" class="block py-2 pl-3 pr-4">Contacts</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@include('partials.confirm_modal')
