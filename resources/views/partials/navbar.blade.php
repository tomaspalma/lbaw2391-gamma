<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search.js'])
</head>

<nav class="bg-white border-black border-b mb-5 p-1.5">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <span class="self-center text-2xl font-bold">Gamma</span>

        <div class="flex flex-col items-center md:order-1">
            <div>
                <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search"
                    aria-expanded="false"
                    class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 mr-1">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
                <form id="search-form" class="relative hidden md:block">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search icon</span>
                    </div>
                    <input name="search" type="text" id="search-navbar"
                        class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search...">
                </form>
            </div>
            <div id="search-preview-results" class="absolute hidden mt-10 border w-1/3 bg-white rounded-4 p-4 shadow-xl">
                <ul id="preview-results" class="center justify-center flex border border-black rounded shadow my-4 cursor-pointer">
                    <li data-id="selected" id="users-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center">
                        Users
                    </li>
                    <li id="posts-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center border-t-4 border-black">
                        Posts
                    </li>
                    <li id="groups-preview-results" class="preview-results-option flex w-1/2 p-2 justify-center">
                        Groups
                    </li>
                </ul>
                <div id="search-preview-content"></div>
            </div>
        </div>
        <div class="items-center w-full md:flex md:w-auto md:order-1">
            <ul
                class="flex flex-col p-4 md:p-0 mt-4 font-medium bg-gray-50 md:flex-row md:space-x-2 md:mt-0 md:border-0 md:bg-white">
                <li>
                    <a href="#" class="block py-2 pl-3 pr-4">Home</a>
                </li>
                <li>
                    <a href="#" class="block py-2 pl-3 pr-4">Home</a>
                </li>
                <li>
                    <a href="#" class="block py-2 pl-3 pr-4">Home</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
