<head>
    @extends('layouts.head')
</head>

@include('partials.navbar')

<main class="center">
    <h1 class="text-center text-2xl bold mt-4">
        Block User
    </h1>
    <div class="flex justify-center">
        <article class="flex flex-col align-middle justify-center mt-4">
            <img class="rounded-full w-14 h-15" src="{{$user->image}}" alt="Profile Picture">
            <h1>
                <a href="/user/${user.username}" class="underline">
                    {{$user->username}}
                </a>
            </h1>

        </article>
    </div>
    <form method="POST" action="/users/{{$user->username}}/block">
        <input name="_token" value="{{csrf_token()}}" hidden>
        <label for="block-reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Block reason</label>
        <textarea required name="reason" id="block-reason" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Explain to {{$user->username}} why they are being blocked"></textarea>
        <button type="submit" class="bg-black p-2 text-white mt-2 rounded-s w-full">Submit</button>
    </form>
</main>
