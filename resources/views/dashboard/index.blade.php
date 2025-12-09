@extends('layouts.app')

@section('content')

<h2>🏰 Panel Czarodzieja</h2>

<p>Witaj, {{ $user->name }}! Oto aktualne dane z Hogwartu.</p>



{{-- ============== RANKING DOMÓW ============== --}}
<h2>⚔️ Ranking Domów</h2>

<table>
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
                    <img src="/images/houses/{{ strtolower($house->name) }}.jpg" 
                         class="house-badge">
                </td>
                <td>{{ $house->name }}</td>
                <td>{{ $house->total_points }}</td>
            </tr>
        @endforeach
    </tbody>
</table>



{{-- ============== TOP NAUCZYCIELE ============== --}}
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
