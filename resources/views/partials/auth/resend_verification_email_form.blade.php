<form class="inline" method="POST" action="{{route('verification.send')}}">
    @csrf
    <button type="submit" class="hover:underline">Click here to resend</button>
</form>
