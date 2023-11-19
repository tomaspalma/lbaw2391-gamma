<head>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/components/confirmation_modal.js', 'resources/js/components/navbar_mobile_menu.js'])
</head>

<div id="confirmation-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 z-40 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="bg-black opacity-70 absolute inset-0"></div>
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="p-6 text-center">
                <div class="text-right">
                    <i class="close-confirmation-modal fa-solid fa-x p-4 hover:cursor-pointer"></i>
                </div>
                <div class="flex justify-between">
                    <svg id="info-icon" class="mx-auto mb-4 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h3 id="confirmation-modal-delete-message" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">

                </h3>
                <form id="confirmation-form">
                    <input name="_token" value="{{csrf_token()}}" hidden>
                    <button id="action-confirmation-modal" type="submit" class="text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes
                    </button>
                    <button type="button" class="close-confirmation-modal text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        No
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
