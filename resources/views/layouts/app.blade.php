<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dziennik Hogwartu</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background: #f7f7f7; }
        header { background: #222; color: #fff; padding: 10px 20px; display:flex; justify-content:space-between; align-items:center; }
        main { padding: 20px; }
        .btn { padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer; }
        .btn-primary { background: #007bff; color: #fff; }
        .btn-danger  { background: #dc3545; color: #fff; }
        table { border-collapse: collapse; width: 100%; background:#fff; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align:left; }
        th { background:#eee; }
    </style>
</head>
<body>
<header>
    <div><strong>Dziennik Hogwartu</strong></div>
    <div>
        @auth
            Zalogowany jako: {{ auth()->user()->name }} {{ auth()->user()->surname }}
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn btn-danger" type="submit">Wyloguj</button>
            </form>
        @endauth
    </div>
</header>
<main>
    @yield('content')
</main>
</body>
</html>
