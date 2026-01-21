<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dziennik Hogwartu</title>

    <style>
        /* GLOBAL MAGIC STYLE */

        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background: url('/images/hogwarts_wall.svg') center/cover fixed;
            color: #2b2b2b;
        }

        /* Pergaminowa karta */
        .scroll {
            background: url('/images/pergamin_texture.svg') no-repeat center/cover;
            margin: 30px auto;
            padding: 40px;
            max-width: 1000px;
            border: 4px solid #3a2e1e;
            box-shadow: 0 0 20px rgba(0,0,0,0.6);
            border-radius: 8px;
        }

        header {
            background: rgba(0,0,0,0.75);
            color: #f0e6d2;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #b8860b;
        }

        header h1 {
            margin: 0;
            font-family: 'Georgia', serif;
            letter-spacing: 2px;
        }

        .logout-btn {
            background: #7f0909;
            border: none;
            padding: 8px 14px;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #a30c0c;
        }

        h2 {
            border-bottom: 2px solid #3a2e1e;
            padding-bottom: 6px;
            margin-top: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            background: rgba(255,255,255,0.85);
        }

        th, td {
            padding: 8px;
            border: 1px solid #b9a37a;
        }

        th {
            background: #e8d7b9;
        }

        /* HERBY DOMÓW */
        .house-badge {
            width: 50px;
            /* height: 50px; */
        }

        footer {
            text-align: center;
            color: #e0d9cd;
            margin-top: 30px;
            padding-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>

<header>
    <h1>🪄 Dziennik Hogwartu</h1>
    <div>
        @guest
            <a href="{{ route('login') }}">
                <img src="/images/wiedzma.png"  style="width:40px"/>
            </a>
        @endguest
        @auth
            <div>
                <a href="{{ route('public.houses') }}" style="color:#f0e6d2; text-decoration:none; margin-right:15px;">
                    🏆 Ranking domów
                </a>
                <a href="{{ route('dashboard') }}" style="color:#f0e6d2; text-decoration:none; margin-right:15px;">
                    🪄 Dashboard
                </a>
                {{ auth()->user()->name }} {{ auth()->user()->surname }}

                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="logout-btn">Wyloguj</button>
                </form>
            </div>
        @endauth
    </div>
</header>

<div class="scroll">
    @yield('content')
</div>

<footer>
    © {{ date('Y') }} Hogwart — Szkoła Magii i Czarodziejstwa
</footer>

</body>
</html>
