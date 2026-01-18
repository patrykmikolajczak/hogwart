@extends('layouts.app')

@section('content')

<style>
    .public-ranking-wrapper {
        text-align: center;
    }

    .houses-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .house-card {
        background: rgba(255,255,255,0.9);
        border: 1px solid #b9a37a;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }

    .house-card img {
        width: 70px;
        /* height: 70px; */
    }

    .house-name {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
    }

    .house-points {
        font-size: 22px;
        margin-top: 5px;
    }

    .house-1 { border-color:#ffd700; box-shadow:0 0 12px rgba(255,215,0,0.9); }
    .house-2 { opacity:0.95; }
    .house-3 { opacity:0.9; }
    .house-4 { opacity:0.85; }

    .house-card small {
        display:block;
        margin-top: 5px;
        font-size: 12px;
        color:#555;
    }
</style>

<div class="public-ranking-wrapper">
    <h2>🏆 Ranking Domów Hogwartu</h2>
    <p>
        Strona dostępna dla każdego – nawet dla mugoli. <br>
        Tu zobaczysz, który dom aktualnie prowadzi w rywalizacji o Puchar Domów.
    </p>

    <div class="houses-grid">
        @php
            // posortowane już w PointsService, ale na wszelki wypadek
            $sorted = $housesRanking->sortByDesc('total_points')->values();
        @endphp

        @foreach($sorted as $index => $house)
            @php
                $position = $index + 1;
                $cssClass = 'house-' . $position;

                $imgName = strtolower($house->name); // Gryffindor -> gryffindor.svg
                $pts = (int) $house->total_points;
            @endphp

            <div class="house-card {{ $cssClass }}">
                <img src="/images/houses/{{ $imgName }}.jpg" alt="{{ $house->name }}">
                <div class="house-name">
                    {{ $position }}. {{ $house->name }}
                </div>
                <div class="house-points">
                    {{ $pts }} pkt
                </div>
                @if($position === 1)
                    <small>🏅 Aktualny lider Pucharu Domów!</small>
                @endif
            </div>
        @endforeach
    </div>

    <p style="margin-top:20px; font-size:13px;">
        Dane odświeżane przy każdym wejściu na stronę. <br>
        Uczniowie i nauczyciele mogą wpływać na wynik logując się do dziennika.
    </p>
</div>

@endsection
