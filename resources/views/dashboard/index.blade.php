@extends('layouts.app')

@section('content')
    <h1>Panel Dziennika Hogwartu</h1>

    <h2>Ranking Domów</h2>
    <table>
        <thead>
        <tr>
            <th>Dom</th>
            <th>Punkty</th>
        </tr>
        </thead>
        <tbody>
        @foreach($housesRanking as $house)
            <tr>
                <td>{{ $house->name }}</td>
                <td>{{ $house->total_points }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2 style="margin-top: 30px;">Top Nauczyciele (wg przyznanych punktów)</h2>
    <table>
        <thead>
        <tr>
            <th>Nauczyciel</th>
            <th>Punkty</th>
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

    @if($classRanking)
        <h2 style="margin-top: 30px;">Ranking klasy (Twoja klasa)</h2>
        <table>
            <thead>
            <tr>
                <th>Uczeń</th>
                <th>Punkty</th>
            </tr>
            </thead>
            <tbody>
            @foreach($classRanking as $s)
                <tr @if($s->user_id === auth()->id()) style="font-weight:bold;" @endif>
                    <td>{{ $s->name }} {{ $s->surname }}</td>
                    <td>{{ $s->total_points }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
