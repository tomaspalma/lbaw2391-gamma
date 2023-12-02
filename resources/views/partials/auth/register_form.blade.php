<head>
    @vite(['resources/css/app.css', 'resources/js/auth/register.js', 'resources/js/auth/seePassword.js'])

    <link href="{{ url('css/post.css') }}" rel="stylesheet">
</head>

@if ($admin_page_version && session('success'))
<h1 class="text-2xl mt-2 text-center mb-4 text-green-700">
    {{ session('success') }}
</h1>
@endif

<div class="flex justify-center mt-2">
    <form method="POST" class="border-2 border-gray-500 p-4 w-96 max-w-screen-md justify-self: center" action="{{ route('register') }}">
        {{ csrf_field() }}
        <input name="_token" value="{{ csrf_token() }}" hidden>

        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-600">Username</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>

            <div id="username-error" class="text-red-500 text-sm"></div>

            @if ($errors->has('username'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('username') }}
            </span>
            @endif
        </div>

        <div class="mb-4">
            <label for="display_name" class="block text-sm font-medium text-gray-600">Display Name</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="display_name" type="text" name="display_name" value="{{ old('display_name') }}" required autofocus>
            @if ($errors->has('display_name'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('display_name') }}
            </span>
            @endif
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-600">E-Mail Address</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="email" type="email" name="email" value="{{ old('email') }}" required>

            <div id="email-error" class="text-red-500 text-sm"></div>



            @if ($errors->has('email'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('email') }}
            </span>
            @endif
        </div>

        <div class="mb-4">
            <label for="academic_status" class="block text-sm font-medium text-gray-600">Academic Status</label>
            <select name="academic_status" class="mt-1 p-2 w-full border focus:ring-2">
                <option value="student" selected>Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="is_private" class="block text-sm font-medium text-gray-600">Privacy</label>
            <select name="is_private" class="mt-1 p-2 w-full border focus:ring-2">
                <option value="yes" selected>Private</option>
                <option value="no">Public</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="password" type="password" name="password" required>

            <i class="fas fa-eye-slash cursor-pointer" id="togglePassword" style="margin-top: -29px; margin-left: 310px;"></i>

            @if ($errors->has('password'))
            <span class="text-red-500 text-sm">
                {{ $errors->first('password') }}
            </span>
            @endif

        </div>

        <div class="mb-4">
            <label for="password-confirm" class="block text-sm font-medium text-gray-600">Confirm Password</label>
            <input class="mt-1 p-2 w-full border focus:ring-2" id="password-confirm" type="password" name="password_confirmation" required>

            <i class="fas fa-eye-slash cursor-pointer" id="togglePasswordConfirm" style="margin-top: -29px; margin-left: 310px;"></i>

            <div class="flex items-center">
                <button class="bg-blue-500 text-white py-2 px-4 rounded {{$admin_page_version ? 'w-full' : ''}}" type="submit">
                    {{$admin_page_version ? "Create User" : "Register"}}
                </button>

                @if(!$admin_page_version)
                <a class="ml-2 text-blue-500" href="{{ route('login') }}">Login</a>
                @endif
            </div>
    </form>
</div>
