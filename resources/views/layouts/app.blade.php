<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dziennik</title>
    <link rel="shortcut icon" href="/images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .hp-nav {
            background: url("/images/btn.png");
        }
        .ribbon-button {
            position: relative;
            display: inline-block;
            width: 340px;              /* dopasuj do grafiki */
            height: 100px;
            background-image: url("/images/btn.png"); /* Twoja wstążka */
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            text-decoration: none;
        }

        .ribbon-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-54%, -58%);

            font-family: "Cinzel", "Trajan Pro", "Georgia", serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;

            color: #3a2a14;
            text-shadow:
                0 1px 0 #f5e6c8,
                0 2px 4px rgba(0,0,0,0.4);

            white-space: nowrap;
            pointer-events: none; /* klik działa na cały przycisk */
        }
    </style>
</head>
<body class="text-bg-dark" style="background: black !important">
    <header class="p-3 text-bg-dark" style="background: black !important">
        <div class="container"> 
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <!-- <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                </a> -->
                
                @auth
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="{{ route('dashboard') }}" class="nav-link px-2 text-secondary"><img src="/images/dashboard.png" width="150px;"/></a></li>
                    <li><a href="{{ route('public.houses') }}" class="nav-link px-2 text-white"><img src="/images/punkty.png" width="150px;"/></a></li>
                    <li><a href="{{ route('public.tournament') }}" class="nav-link px-2 text-white"><img src="/images/turniej.png" width="150px;"/></a></li>
                    <li><a href="{{ route('statistics') }}" class="nav-link px-2 text-white"><img src="/images/statystyki.png" width="150px;"/></a></li>
                    <li><a href="{{ route('settings.index') }}" class="nav-link px-2 text-white"><img src="/images/ustawienia.png" width="150px;"/></a></li>
                </ul>
                @endauth
                @guest
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="{{ route('public.houses') }}" class="nav-link px-2 text-white"><img src="/images/punkty.png" width="150px;"/></a></li>
                    <li><a href="{{ route('public.tournament') }}" class="nav-link px-2 text-white"><img src="/images/turniej.png" width="150px;"/></a></li>
                </ul>
                @endguest
                <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                    <input type="search"
                    class="form-control form-control-dark text-bg-dark"
                    placeholder="Search..." aria-label="Search">
                </form> -->
                <div class="text-end">
                    @guest
                        <a href="{{ route('login') }}">
                            <img src="/images/wiedzma.png"  style="width:40px"/>
                        </a>
                    @endguest
                    @auth
                        <!-- <a href="{{ route('login') }}">
                            <img src="/images/wiedzma.png"  style="width:40px"/>
                        </a> -->
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <img src="/images/logout.png"  style="width:200px" onclick="this.closest('form').submit(); return false;"/>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <header>
        
        @auth
        @endauth
    </header>
    

    <div class="container">
        @auth
        <div class="row g-5">
            <div class="col-md-12">
                <div class="row text-center">
                    <div class="col-12 col-md">
                        <a href="{{ route('teacher.points.bulk.create') }}" class="ribbon-button">
                            <span class="ribbon-text">Przyznaj punkty uczniom</span>
                        </a>
                        <!-- <a class="hp-nav" href="{{ route('teacher.points.bulk.create') }}">✨ Przyznaj punkty uczniom</a> -->
                    </div>
                    <div class="col-12 col-md">
                        <a href="{{ route('teacher.points.houses.create') }}" class="ribbon-button">
                            <span class="ribbon-text">Przyznaj punkty domom</span>
                        </a>
                        <!-- <a class="hp-nav" href="{{ route('teacher.points.houses.create') }}">✨ Przyznaj punkty domom</a> -->
                    </div>
                    <div class="col-12 col-md">
                        <a href="{{ route('teacher.points.history') }}" class="ribbon-button">
                            <span class="ribbon-text">Historia zaklęć</span>
                        </a>
                        <!-- <a class="hp-nav" href="{{ route('teacher.points.history') }}">📜 Historia zaklęć</a> -->
                    </div>
                </div>
            </div>
        </div>
        @endauth
        <div class="row g-5">
            <div class="col-md-12 text-center">
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <div class="col-md d-flex align-items-center">
            <span class="mb-3 mb-md-0">© {{ date('Y') }} Prymus — Szkoła Magii i Czarodziejstwa</span>
        </div>
    </footer>
</body>
</html>