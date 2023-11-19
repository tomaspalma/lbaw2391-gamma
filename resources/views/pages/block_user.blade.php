<form method="POST" action="/users/{{$user->username}}/block">
    <input name="_token" value="{{csrf_token()}}" hidden>
    <label for="block-reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Block reason</label>
    <textarea required name="reason" id="block-reason" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Explain to {{$user->username}} why they are being blocked"></textarea>
    <button type="submit" class="bg-black p-2 text-white mt-2 rounded-s w-full">Submit</button>
</form>
