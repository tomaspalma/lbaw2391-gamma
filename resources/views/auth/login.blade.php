@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label class="text-blue-500 py-1 px-2" for="email">E-mail</label>
    <input class = "border focus:ring-2" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label class = "text-blue-500 py-1 px-2" for="password" >Password</label>
    <input class ="biorder focus:ring-2" id="password" type="password" name="password" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label class = "text-blue-500">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button class = "text-blue-500" type="submit">
        Login
    </button>
    <a class="button button-outline text-blue-500" href="{{ route('register') }}">Register</a>
    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
@endsection