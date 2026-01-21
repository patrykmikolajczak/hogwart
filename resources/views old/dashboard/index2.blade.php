@extends('layouts.app2')

@section('content')

<h2>🏰 Panel Czarodzieja</h2>

<p>Witaj, {{ $user->name }}! Oto aktualne dane z Hogwartu.</p>


<h2>⚔️ Ranking Domów</h2>

<div class="row">
    @foreach($housesRanking as $house)
            <div class="col-md-3 text-center">
                <img src="/images/{{ strtolower($house->name) }}.png" 
                        class="house-badge" style="width:200px;">
                <div class="house-points">
                    {{ $house->total_points }} pkt
                </div>
            </div>
    @endforeach
</div>

<!-- <table>
    <thead>
        <tr>
            <th>Herb</th>
            <th>Dom</th>
            <th>Punkty</th>
        </tr>
    </thead>
    <tbody>
        @foreach($housesRanking as $house)
            <tr>
                <td>
                    <img src="/images/{{ strtolower($house->name) }}.png" 
                         class="house-badge" style="width:200px;">
                </td>
                <td>{{ $house->name }}</td>
                <td>{{ $house->total_points }}</td>
            </tr>
        @endforeach
    </tbody>
</table> -->



{{-- ============== TOP NAUCZYCIELE ============== --}}
<div class="row mb-5">
    <div class="col-12 col-md-6">
        <h2>🧙‍♂️ Najaktywniejsi Nauczyciele</h2>

        <table>
            <thead>
                <tr>
                    <th>Nauczyciel</th>
                    <th>Punkty przyznane</th>
                </tr>
            </thead>
            <tbody>
            @foreach($topTeachers as $t)
                <tr>
                    <td>{{ $t->name }} {{ $t->surname }}</td>
                    <td>{{ $t->total_points }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12 col-md-6">
        <h2>🧙 Najaktywniejsze klasy</h2>

        <table>
            <thead>
                <tr>
                    <th>Klasa</th>
                    <th>Punkty przyznane</th>
                </tr>
            </thead>
            <tbody>
            @foreach($topClasses as $t)
                <tr>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->total_points }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>



{{-- ============== RANKING KLASY ============== --}}
@if($classRanking)
<h2>📘 Ranking Twojej Klasy</h2>

<table>
    <thead>
        <tr>
            <th>Uczeń</th>
            <th>Punkty</th>
        </tr>
    </thead>
    <tbody>
    @foreach($classRanking as $s)
        <tr @if($s->user_id === auth()->id()) style="font-weight:bold; background:#f0e6d2;" @endif>
            <td>{{ $s->name }} {{ $s->surname }}</td>
            <td>{{ $s->total_points }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif

@endsection
