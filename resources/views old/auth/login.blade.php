@extends('layouts.app')

@section('content')

<h2>Wejście do Hogwartu</h2>
<p>Aby dostać się na teren zamku, podaj swoje dane.</p>

@if ($errors->any())
    <div style="color: darkred; background:#f8d7da; padding:10px; margin-bottom:15px;">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('login.post') }}">
    @csrf

    <label for="login">Login czarodzieja:</label><br>
    <input type="text" name="login" id="login"
           style="width: 300px; padding:6px; margin-top:6px;"><br><br>

    <label for="password">Hasło:</label><br>
    <input type="password" name="password" id="password"
           style="width: 300px; padding:6px; margin-top:6px;"><br><br>

    <label><input type="checkbox" name="remember"> Zapamiętaj mnie</label><br><br>

    <button style="padding:10px 18px; background:#3a2e1e; color:white; border:0;">Zaloguj</button>
</form>

@endsection
