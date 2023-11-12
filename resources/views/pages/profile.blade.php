<head>
    @vite('resources/css/app.css')
</head>

<div class="max-w-screen-md mx-auto p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-700">User Profile</h2>
        </div>
        <div class="mt-6 flex flex-col md:flex-row -mx-3">
            <div class="md:flex-1 px-3">
                <div class="mb-4">
                    <img src="https://picsum.photos/200" alt="User Profile" class="rounded-full">
                </div>
                <div class="flex items-end mb-4">
                    <label class="text-xl font-bold text-gray-700">{{$user->display_name}}</label>
                        <span class="ml-2 text-xs text-gray-600 pb-1">
                        {{$user->is_private ? 'Private' : 'Public'}}
                        </span>
                </div>
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Username</label>
                    <div class="font-semibold text-gray-800">{{$user->username}}</div>
                </div>
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Email</label>
                    <div class="font-semibold text-gray-800">{{$user->email}}</div>
                </div>
                <div class="mb-4">
                    <label class="text-sm text-gray-600">Academic Status</label>
                    <div class="font-semibold text-gray-800">{{$user->academic_status}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
