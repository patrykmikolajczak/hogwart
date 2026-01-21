@extends('layouts.app2')

@section('content')
<!-- <div class="row g-5">
    <div class="col-md-12"> -->
        <h2>🏆 Ranking Turnieju Czwórmagicznego</h2>
        <p>
            Strona dostępna dla każdego – nawet dla mugoli. <br>
            Tu zobaczysz, który dom aktualnie prowadzi w rywalizacji o Puchar Domów.
        </p>

        <div class="row g-5">
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

                <div class="col-md-3 text-center">
                <!-- <div class="house-card {{ $cssClass }}"> -->
                    <img src="/images/{{ $imgName }}.png" class="img-fluid" alt="{{ $house->name }}">
                    <!-- <div class="house-name">
                        {{ $position }}. {{ $house->name }}
                    </div> -->
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
    <!-- </div>
</div> -->

@endsection
