@extends('layouts.app')

@section('content')

<h2>🏰 Panel Czarodzieja</h2>

<p>Witaj, {{ $user->name }}! Oto aktualne dane z Hogwartu.</p>

@if($user->is_teacher)
    <div style="margin: 10px 0 25px 0;">
        <a href="{{ route('teacher.points.create') }}"
           style="display:inline-block; padding:10px 18px; background:#7f0909; color:#fff;
                  text-decoration:none; border-radius:6px; border:2px solid #ffc500;">
            🪄 Przyznaj punkty uczniom
        </a>
    </div>
@endif
@if($user->is_teacher)
<div style="margin: 10px 0 20px 0;">
    <a href="{{ route('teacher.points.create') }}"
       style="margin-right: 10px; text-decoration:none; padding:6px 10px;
              border-radius:6px; border:1px solid #b9a37a; background:#f5ebd7;">
        🪄 Przyznaj punkty
    </a>

    <a 
        href="{{ route('teacher.points.bulk.create') }}"
        style="margin-right: 10px; text-decoration:none; padding:6px 10px;
              border-radius:6px; border:1px solid #b9a37a; background:#f5ebd7;"
    >
        ✨ Przyznaj punkty seryjnie
    </a>

    <a href="{{ route('teacher.points.history') }}"
       style="text-decoration:none; padding:6px 10px;
              border-radius:6px; border:1px solid #b9a37a; background:#f5ebd7;">
        📜 Historia zaklęć (punktów)
    </a>
</div>
@endif



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
