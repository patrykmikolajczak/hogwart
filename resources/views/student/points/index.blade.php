@extends('layouts.app')

@section('content')

<style>
    .student-points-summary {
        padding: 10px 15px;
        background: rgba(255,255,255,0.9);
        border: 1px solid #b9a37a;
        margin-bottom: 15px;
    }

    .student-points-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255,255,255,0.9);
        margin-top: 10px;
    }

    .student-points-table th,
    .student-points-table td {
        border: 1px solid #b9a37a;
        padding: 6px 8px;
        font-size: 14px;
    }

    .student-points-table th {
        background: #e8d7b9;
    }

    .pts-plus {
        color: #155724;
        font-weight: bold;
    }

    .pts-minus {
        color: #721c24;
        font-weight: bold;
    }

    .badge-house {
        display:inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
        color:#fff;
    }
    .badge-gryffindor { background:#7f0909; }
    .badge-slytherin  { background:#0d6217; }
    .badge-ravenclaw  { background:#0e1a40; }
    .badge-hufflepuff { background:#eee117; color:#000; }

    .nav-student {
        margin: 10px 0 20px 0;
    }

    .nav-student a {
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #b9a37a;
        background: #f5ebd7;
        margin-right: 8px;
        font-size: 14px;
    }
    .nav-student a:hover {
        background: #e8d1a7;
    }
</style>

<h2>📗 Moje punkty w Hogwarcie</h2>

<div class="nav-student">
    <a href="{{ route('dashboard') }}">🏰 Panel główny</a>
    <a href="{{ route('student.points.index') }}">📗 Historia moich punktów</a>
    <a href="{{ route('public.houses') }}">🏆 Ranking domów</a>
</div>

@php
    $house = $student->house->name ?? null;
    $houseClass = '';
    if ($house === 'Gryffindor')  $houseClass = 'badge-house badge-gryffindor';
    if ($house === 'Slytherin')   $houseClass = 'badge-house badge-slytherin';
    if ($house === 'Ravenclaw')   $houseClass = 'badge-house badge-ravenclaw';
    if ($house === 'Hufflepuff')  $houseClass = 'badge-house badge-hufflepuff';
@endphp

<div class="student-points-summary">
    <strong>Uczeń:</strong> {{ $student->name }} {{ $student->surname }}<br>
    <strong>Klasa:</strong> {{ $student->class->name ?? '—' }}<br>
    <strong>Dom:</strong>
    @if($houseClass)
        <span class="{{ $houseClass }}">{{ $house }}</span>
    @else
        {{ $house ?? 'Brak przydziału' }}
    @endif
    <br>
    <strong>Łączna liczba punktów:</strong>
    <span style="font-size: 18px; font-weight:bold;
        color: {{ $totalPoints >= 0 ? '#155724' : '#721c24' }};">
        {{ $totalPoints > 0 ? '+' . $totalPoints : $totalPoints }}
    </span>
</div>

@if($points->isEmpty())
    <p>Nie otrzymałeś jeszcze żadnych punktów. Czas zabłysnąć na lekcjach! ✨</p>
@else
    <table class="student-points-table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Nauczyciel</th>
                <th>Przedmiot</th>
                <th>Punkty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($points as $p)
                @php
                    $teacher = $p->teacher;
                    $subject = $p->subject?->name ?? '—';
                    $created = $p->created_at?->format('Y-m-d H:i') ?? '';
                    $pts     = $p->points;
                    $classPts = $pts >= 0 ? 'pts-plus' : 'pts-minus';
                @endphp
                <tr>
                    <td>{{ $created }}</td>
                    <td>
                        @if($teacher)
                            {{ $teacher->surname }} {{ $teacher->name }}
                        @else
                            [nauczyciel usunięty]
                        @endif
                    </td>
                    <td>{{ $subject }}</td>
                    <td class="{{ $classPts }}">
                        @if($pts > 0)
                            +{{ $pts }}
                        @else
                            {{ $pts }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $points->links() }}
    </div>
@endif

@endsection
