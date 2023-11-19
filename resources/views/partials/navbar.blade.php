<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search/search_input_preview.js', 'resources/js/components/navbar_mobile_menu.js'])
</head>
<nav class="bg-white border-black border-b mb-5 p-1.5">
    <div class="max-w-screen-xl flex flex-col md:flex-row flex-wrap justify-between mx-auto p-4">
        <a href="/" class="self-center text-2xl font-bold hover:underline">Gamma</a>
        <div class="flex flex-col items-center md:order-1">
            <input name="search" type="text" id="mobile-search-trigger" class="mt-4 md:hidden block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
            <form id="search-form" class="relative hidden md:block">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span class="sr-only">Search icon</span>
                </div>
                <input name="search" type="text" id="search-navbar" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search...">
            </form>

            @include('partials.search.search_preview', [
            'hidden' => true,
            'previewMenuShadow' => false,
            'previewMenuWidth' => 'w-full',
            'previewMenuPosAbs' => false,
            'previewMenuName' => 'search-input',
            'toggled' => 'users',
            'isMobile' => true
            ])

            @include('partials.search.search_preview', [
            'hidden' => true,
            'previewMenuShadow' => true,
            'previewMenuWidth' => 'w-1/3',
            'previewMenuPosAbs' => true,
            'previewMenuName' => 'search-input',
            'toggled' => 'users',
            'isMobile' => false
            ])
        </div>
        <div class="items-center w-full md:flex md:w-auto md:order-1">
            <i id="hamburger-toggle" class="md:hidden block fa-solid fa-bars mt-4 cursor-pointer"></i>
            <ul id="navbar-menu" class="hidden md:flex flex flex-col justify-center align-middle items-center p-4 md:p-0 mt-4 font-medium bg-gray-50 md:flex-row md:space-x-2 md:mt-0 md:border-0 md:bg-white">
                <li>
                    <a href="/feed" class="block py-2 pl-3 pr-4">
                        Home
                    </a>
                </li>
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
                    <form method="GET" action="{{ route('logout') }}" class="block py-2 pl-3 pr-4 mb-0">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
                @endauth
                <li>
                    <a href="#" class="block py-2 pl-3 pr-4">
                        <i class="hidden md:inline fa-solid fa-bell"></i>
                        <span class="md:hidden">Notifications</span>
                    </a>
                </li>
                @auth
                <li class="flex items-center space-x-0">
                    <a href="/users/{{Auth::user()->username}}" class="block py-2 pl-3 pr-1">
                        Profile
                    </a>
                </li>
                @endauth
            </ul>
        </div>

    </div>
</nav>
