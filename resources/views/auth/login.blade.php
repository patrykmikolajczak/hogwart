@extends('layouts.app')

@section('content')
    <h1>Logowanie</h1>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div>
            <label for="login">Login:</label><br>
            <input type="text" name="login" id="login" value="{{ old('login') }}">
        </div>

        <div style="margin-top: 10px;">
            <label for="password">Hasło:</label><br>
            <input type="password" name="password" id="password">
        </div>

        <div style="margin-top: 10px;">
            <label>
                <input type="checkbox" name="remember">
                Zapamiętaj mnie
            </label>
        </div>

        <div style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary">Zaloguj</button>
        </div>
    </form>
@endsection
