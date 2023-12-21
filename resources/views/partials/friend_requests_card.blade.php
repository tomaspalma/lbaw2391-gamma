<div class="flex justify-between items-center bg-white p-4 m-2 rounded shadow" id="request-{{$request->sender->username}}">
    <div class="flex items-center space-x-4">
        <img src="{{ $request->sender->getProfileImage('small') }}" alt="{{ $request->sender->username }}'s Profile Image" class="w-12 h-12 rounded-full">
        <div>
            <a href="{{'/users/' . $request->sender->username}}">
                <h2 class="text-xl font-bold">{{ $request->sender->display_name }}</h2>
                <p class="text-gray-500">{{ $request->sender->username }}</p>
            </a>
        </div>
    </div>
    <form id="friendRequestForm" data-username="{{ $request->sender->username }}" class="flex items-center space-x-4 my-auto" method="post">
        @csrf
        <button type="submit" name="action" value="accept" class="form-button-blue font-bold py-2 px-4 rounded">Accept</button>
        <button type="submit" name="action" value="decline" class="form-button-red font-bold py-2 px-4 rounded">Decline</button>
    </form>
</div>
