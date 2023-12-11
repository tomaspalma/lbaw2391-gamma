<article class="my-4 p-2 border-b flex flex-col justify-between align-middle space-x-2">
    <div class="flex flex-row justify-between">
    <div class="flex flex-row space-x-2 align-middle">
            <img class="rounded-full w-10 h-10" src="{{$group->getProfileImage()}}" alt="Profile Picture">
            <h1>
                <a href = "/group/{{$group->id}}" class="underline">
                    {{$group->name}}
                </a>
            </h1>
        </div>
    </div>
</article>
