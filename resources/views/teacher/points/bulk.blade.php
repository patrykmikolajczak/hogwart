@extends('layouts.app')

@section('content')

<style>
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

    .filter-row {
        margin-bottom: 15px;
        padding: 10px 12px;
        background: rgba(255,255,255,0.9);
        border: 1px solid #b9a37a;
    }

    .filter-row select {
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #b9a37a;
        margin-right: 10px;
        font-family: 'Georgia', serif;
    }

    .filter-row button {
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #3a2e1e;
        background: #3a2e1e;
        color: #fff;
        cursor:pointer;
    }

    .bulk-table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255,255,255,0.9);
        margin-top: 10px;
    }

    .bulk-table th,
    .bulk-table td {
        border: 1px solid #b9a37a;
        padding: 6px 8px;
        font-size: 14px;
    }

    .bulk-table th {
        background: #e8d7b9;
    }

    .spell-input {
        width: 70px;
        padding: 4px 6px;
        border-radius: 4px;
        border: 1px solid #b9a37a;
        text-align: center;
    }

    .spell-btn {
        padding: 10px 20px;
        background: #7f0909;
        color: #fff;
        border: 2px solid #ffc500;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        box-shadow: 0 0 8px rgba(0,0,0,0.3);
        margin-top: 15px;
    }

    .spell-btn:hover {
        background: #a30c0c;
    }

    .alert-success {
        background: #d4edda;
        border: 1px solid #155724;
        color: #155724;
        padding: 10px 12px;
        margin-bottom: 15px;
    }

    .alert-error {
        background: #f8d7da;
        border: 1px solid #721c24;
        color: #721c24;
        padding: 10px 12px;
        margin-bottom: 15px;
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
    .badge-ravenclow  { background:#0e1a40; }
    .badge-hufflepuff { background:#eee117; color:#000; }
</style>

<h2>✨ Seryjne przyznawanie punktów</h2>

<div class="nav-teacher">
    <a href="{{ route('teacher.points.create') }}">🪄 Przyznaj punkty (pojedynczo)</a>
    <a href="{{ route('teacher.points.bulk.create') }}">✨ Przyznaj punkty seryjnie</a>
    <a href="{{ route('teacher.points.history') }}">📜 Historia zaklęć</a>
</div>

<p>
    {{ $teacher->name }} {{ $teacher->surname }}, tutaj możesz przyznać lub odjąć punkty
    całej klasie naraz – każdemu uczniowi osobno, ale na jednym pergaminie.
</p>

@if (session('status'))
    <div class="alert-success">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert-error">
        <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- KROK 1: wybór klasy i przedmiotu --}}
<div class="filter-row">
    <form method="GET" action="{{ route('teacher.points.bulk.create') }}">
        <label for="class_id">📘 Klasa:</label>
        <select name="class_id" id="class_id">
            <option value="">— wybierz klasę —</option>
            @foreach($classes as $class)
                <option value="{{ $class->class_id }}"
                    {{ (string)$selectedClassId === (string)$class->class_id ? 'selected' : '' }}>
                    {{ $class->name }}
                </option>
            @endforeach
        </select>

        <label for="subject_id" style="margin-left:10px;">📚 Przedmiot:</label>
        <select name="subject_id" id="subject_id">
            <option value="">— wybierz przedmiot —</option>
            @foreach($subjects as $subj)
                <option value="{{ $subj->subject_id }}"
                    {{ old('subject_id') == $subj->subject_id ? 'selected' : '' }}>
                    {{ $subj->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Pokaż uczniów</button>
        <span class="hint" style="font-size:12px; margin-left:8px;">
            Najpierw wybierz klasę i przedmiot, potem wprowadzisz punkty.
        </span>
    </form>
</div>

@if(!$selectedClassId)
    <p>Wybierz klasę i przedmiot powyżej, aby zobaczyć listę uczniów.</p>
@elseif($students->isEmpty())
    <p>W tej klasie nie znaleziono żadnych uczniów.</p>
@else
    {{-- KROK 2: wprowadzanie punktów dla uczniów --}}
    <form method="POST" action="{{ route('teacher.points.bulk.store') }}">
        @csrf

        {{-- przekazujemy klasę i przedmiot --}}
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="subject_id"
               value="{{ old('subject_id', request('subject_id')) }}">

        <table class="bulk-table">
            <thead>
                <tr>
                    <th>Uczeń</th>
                    <th>Dom</th>
                    <th>Punkty (+/-)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $s)
                    @php
                        $house = $s->house->name ?? null;
                        $houseClass = '';
                        if ($house === 'Gryffindor')  $houseClass = 'badge-house badge-gryffindor';
                        if ($house === 'Slytherin')   $houseClass = 'badge-house badge-slytherin';
                        if ($house === 'Ravenclow')   $houseClass = 'badge-house badge-ravenclow';
                        if ($house === 'Hufflepuff')  $houseClass = 'badge-house badge-hufflepuff';
                    @endphp
                    <tr>
                        <td>{{ $s->surname }} {{ $s->name }}</td>
                        <td>
                            @if($houseClass)
                                <span class="{{ $houseClass }}">{{ $house }}</span>
                            @else
                                {{ $house ?? 'Brak' }}
                            @endif
                        </td>
                        <td>
                            <input type="number"
                                   name="points[{{ $s->user_id }}]"
                                   class="spell-input"
                                   min="-50" max="50"
                                   value="{{ old('points.'.$s->user_id, 0) }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="spell-btn">
            🔮 Zapisz wszystkie zaklęcia punktów
        </button>
    </form>
@endif

@endsection
