@extends('layouts.app')

@section('content')

<style>
    .history-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        background: rgba(255,255,255,0.9);
    }

    .history-table th,
    .history-table td {
        border: 1px solid #b9a37a;
        padding: 6px 8px;
        font-size: 14px;
    }

    .history-table th {
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

    .nav-teacher {
        margin: 10px 0 20px 0;
    }

    .nav-teacher a {
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #b9a37a;
        background: #f5ebd7;
        margin-right: 8px;
        font-size: 14px;
    }

    .nav-teacher a:hover {
        background: #e8d1a7;
    }

    .pager {
        margin-top: 15px;
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
</style>

<h2>📜 Historia przyznanych punktów</h2>

<!-- <div class="nav-teacher">
    <a href="{{ route('dashboard') }}">🪄 Dashboard</a> -->
    <!-- <a href="{{ route('teacher.points.create') }}">🪄 Przyznaj punkty</a> -->
    <!-- <a href="{{ route('teacher.points.bulk.create') }}">✨ Przyznaj punkty uczniom</a>
    <a href="{{ route('teacher.points.houses.create') }}">✨ Przyznaj punkty domom</a>
    <a href="{{ route('teacher.points.history') }}">📜 Historia zaklęć</a>
</div> -->

<p>
    {{ $teacher->name }} {{ $teacher->surname }}, oto lista ostatnich zaklęć,
    którymi nagradzałeś lub karałeś uczniów.
</p>

@if($points->isEmpty())
    <p>Nie przyznałeś jeszcze żadnych punktów. Czas rzucić pierwsze zaklęcie! 🪄</p>
@else
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Data</th>
                <th>Uczeń</th>
                <th>Klasa</th>
                <th>Dom</th>
                <th>Przedmiot</th>
                <th>Punkty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($points as $p)
                @php
                    $student = $p->student;
                    $class   = $student?->class?->name ?? '—';
                    $house   = $student?->house?->name ?? 'Brak';
                    $subject = $p->subject?->name ?? '—';
                    $created = $p->created_at?->format('Y-m-d H:i') ?? '';
                    $pts     = $p->points;
                    $classPts = $pts >= 0 ? 'pts-plus' : 'pts-minus';

                    $houseClass = '';
                    if ($house === 'Gryffindor')  $houseClass = 'badge-house badge-gryffindor';
                    if ($house === 'Slytherin')   $houseClass = 'badge-house badge-slytherin';
                    if ($house === 'Ravenclaw')   $houseClass = 'badge-house badge-ravenclaw';
                    if ($house === 'Hufflepuff')  $houseClass = 'badge-house badge-hufflepuff';
                @endphp

                <tr>
                    <td>{{ $created }}</td>
                    <td>
                        @if($student)
                            {{ $student->surname }} {{ $student->name }}
                        @else
                            [uczeń usunięty]
                        @endif
                    </td>
                    <td>{{ $class }}</td>
                    <td>
                        @if($houseClass)
                            <span class="{{ $houseClass }}">{{ $house }}</span>
                        @else
                            {{ $house }}
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

    <div class="pager">
        {{ $points->links() }}
    </div>
@endif

@endsection
