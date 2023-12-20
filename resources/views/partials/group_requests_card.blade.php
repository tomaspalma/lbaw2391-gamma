<article class="my-4 p-2 border-b flex flex-col justify-between align-middle space-x-2 request" id="{{$request->id}}">
    <div class="flex flex-row justify-between">
        <div class="flex flex-row space-x-2 align-middle">
            <img class="rounded-full w-10 h-10" src="{{$request->group->getGroupImage('small')}}" alt="{{$request->group->name}}'s Image">
            <h1>
                <a href="/group/{{$request->group_id}}" class="underline">
                    {{$request->group->name}}
                </a>
            </h1>

            <h1>
                <a href="/users/{{$request->user->username}}" class="underline">
                    {{$request->user->username}}
                </a>
            </h1>
        </div>

        <div class="flex">
            <form data-method="put" class="accept_form ml-auto" action="{{ route('groups.approve_request', $request->id) }}" method="post">
                @csrf
                @method('PUT')
                <button data-type="approve" type="submit" class="form-button-blue font-bold py-2 px-4 rounded accept">
                    Accept
                </button>
            </form>

            <form data-method="delete" class="remove_form ml-4" action="{{ route('groups.decline_request', $request->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button data-type="remove" type="submit" id="leaveGroupButton" class="form-button-red font-bold py-2 px-4 rounded remove">
                    Decline Request
                </button>
            </form>
        </div>
    </div>
</article>
