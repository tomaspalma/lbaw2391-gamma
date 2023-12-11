<article class="my-4 p-2 border-b flex flex-col justify-between align-middle space-x-2">
    <div class="flex flex-row justify-between">
    <div class="flex flex-row space-x-2 align-middle">
            <img class="rounded-full w-10 h-10" src="{{$request->group->getProfileImage()}}" alt="Profile Picture">
            <h1>
                <a href = "/group/{{$request->group_id}}" class="underline">
                    {{$request->group->name}}
                </a>
            </h1>

            <h1>
                <a href = "/users/{{$request->user->username}}" class = "underline">
                    {{$request->user->username}}
                </a>
            </h1>

            <form id="groupForm" action="{{ route('groups.enter', $request->group) }}" method="post">
                @csrf
                <button id="button" type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Accept
                </button>
            </form>


            <form id="groupForm" action="{{ route('groups.leave', $request->group )}}" method="post">
                @csrf
                @method('DELETE')
                <button id="button" type="submit" id="leaveGroupButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Decline Request
                </button>
            </form>
        </div>
    </div>
</article>
