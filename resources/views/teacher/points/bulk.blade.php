@extends('layouts.app')

@section('content')

<!-- <div class="row g-5">
    <div class="col-md-12"> -->
        <h2>✨ Przyznawanie punktów</h2>

        <div class="nav-teacher">
            <!-- <a href="{{ route('dashboard') }}">🪄 Dashboard</a> -->
            <!-- <a href="{{ route('teacher.points.create') }}">🪄 Przyznaj punkty (pojedynczo)</a> -->
            <!-- <a href="{{ route('teacher.points.bulk.create') }}">✨ Przyznaj punkty uczniom</a>
            <a href="{{ route('teacher.points.houses.create') }}">✨ Przyznaj punkty domom</a>
            <a href="{{ route('teacher.points.history') }}">📜 Historia zaklęć</a> -->
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
                    <option value="">wybierz klasę</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->class_id }}"
                            {{ (string)$selectedClassId === (string)$class->class_id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>

                <label for="subject_id" style="margin-left:10px;">📚 Przedmiot:</label>
                <select name="subject_id" id="subject_id">
                    <option value="">wybierz przedmiot</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->subject_id }}"
                            {{ old('subject_id') == $subj->subject_id ? 'selected' : '' }}>
                            {{ $subj->name }}
                        </option>
                    @endforeach
                </select>

                <label for="point_category_id" style="margin-left:10px;">📚 Kategoria:</label>
                <select name="point_category_id" id="point_category_id">
                    <option value="">wybierz kategorie</option>
                    @foreach($pointsCategories as $cat)
                        <option value="{{ $cat->point_category_id }}"
                            {{ (string)$selectedCategoryId === (string)$cat->point_category_id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Pokaż uczniów</button>
                <span class="hint" style="font-size:12px; margin-left:8px;">
                    Najpierw wybierz klasę, przedmiot i kategorię, potem wprowadzisz punkty.
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
                <input type="hidden" name="point_category_id" value="{{ $selectedCategoryId }}">
                <input type="hidden" name="subject_id"
                    value="{{ old('subject_id', request('subject_id')) }}">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Uczeń</th>
                            <th>Dom</th>
                            <th>Przyznane punkty</th>
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
                                <td>+{{ $s->points_plus ?? 0 }}/{{ $s->points_minus ?? 0 }}</td>
                                <td>
                                    <input type="number"
                                        name="points[{{ $s->user_id }}]"
                                        class="spell-input"
                                        min="-50" max="50"
                                        value="">
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
    <!-- </div>
</div> -->

@endsection