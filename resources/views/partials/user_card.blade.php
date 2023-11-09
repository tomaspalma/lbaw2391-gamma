<article class="my-4 p-2 border-b flex align-middle space-x-2">
    <img class="rounded-full w-10 h-10" src="{{$user->image}}" alt="Profile Picture">
    <h1>
        <a href="/user/${user.username}" class="underline">
            {{$user->username}}
        </a>
    </h1>
    @if($adminView)
    <form class="delete-user-form" action="/users/{{$user->username}}">
        <input name="_token" value="{{csrf_token()}}" hidden>
        <button type="submit">
            Delete
        </button>
    </form>
    @endif
</article>`
