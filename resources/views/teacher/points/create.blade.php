@extends('layouts.app')

@section('content')

<style>
    .spell-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 40px;
        margin-top: 15px;
    }

    .spell-form label {
        font-weight: bold;
    }

    .spell-input,
    .spell-select {
        width: 100%;
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #b9a37a;
        background: rgba(255,255,255,0.9);
        font-family: 'Georgia', serif;
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

    .hint {
        font-size: 13px;
        color: #555;
    }
</style>

<h2>🪄 Przyznaj punkty domom Hogwartu</h2>

<p>
    {{ $teacher->name }} {{ $teacher->surname }}, jako nauczyciel możesz
    przyznać lub odjąć punkty uczniom. Pamiętaj, że każde zaklęcie wpływa
    na wynik domów!
</p>

@if($teacher->is_teacher)
<div style="margin: 10px 0 20px 0;">
    <a href="{{ route('dashboard') }}"
       style="margin-right: 10px; text-decoration:none; padding:6px 10px;
              border-radius:6px; border:1px solid #b9a37a; background:#f5ebd7;">
        🪄 Dashboard
    </a>

    <a href="{{ route('teacher.points.history') }}"
       style="text-decoration:none; padding:6px 10px;
              border-radius:6px; border:1px solid #b9a37a; background:#f5ebd7;">
        📜 Historia zaklęć (punktów)
    </a>
</div>
@endif

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

<form method="POST" action="{{ route('teacher.points.store') }}">
    @csrf

    <div class="spell-form">

        {{-- KLASA --}}
        <div>
            <label for="class_id">📘 Klasa</label><br>
            <select name="class_id" id="class_id" class="spell-select">
                <option value="">— wybierz klasę —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->class_id }}"
                        {{ old('class_id') == $class->class_id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            <div class="hint">Najpierw wybierz klasę, uczniowie przefiltrują się automatycznie.</div>
        </div>

        {{-- UCZEŃ --}}
        <div>
            <label for="student_id">🧑‍🎓 Uczeń</label><br>
            <select name="student_id" id="student_id" class="spell-select">
                <option value="">— wybierz ucznia —</option>
                @foreach($students as $s)
                    <option value="{{ $s->user_id }}"
                            data-class="{{ $s->class_id }}"
                        {{ old('student_id') == $s->user_id ? 'selected' : '' }}>
                        {{ $s->surname }} {{ $s->name }}
                        (kl. {{ $s->class->name ?? '?' }},
                         dom: {{ $s->house->name ?? 'brak' }})
                    </option>
                @endforeach
            </select>
            <div class="hint">Lista pokazuje tylko uczniów z wybranej klasy.</div>
        </div>

        {{-- PRZEDMIOT --}}
        <div>
            <label for="subject_id">📚 Przedmiot</label><br>
            <select name="subject_id" id="subject_id" class="spell-select">
                <option value="">— wybierz przedmiot —</option>
                @foreach($subjects as $subj)
                    <option value="{{ $subj->subject_id }}"
                        {{ old('subject_id') == $subj->subject_id ? 'selected' : '' }}>
                        {{ $subj->name }}
                    </option>
                @endforeach
            </select>
            <div class="hint">Tylko przedmioty przypisane do tego nauczyciela.</div>
        </div>

        {{-- PUNKTY --}}
        <div>
            <label for="points">✨ Punkty (mogą być ujemne)</label><br>
            <input type="number" name="points" id="points"
                   class="spell-input"
                   value="{{ old('points', 5) }}"
                   min="-50" max="50">
            <div class="hint">
                Dodatnie za osiągnięcia (np. +5), ujemne za przewinienia (np. -10).
            </div>
        </div>
    </div>

    <div style="margin-top: 25px;">
        <button type="submit" class="spell-btn">
            🔮 Rzuć zaklęcie przyznawania punktów
        </button>
    </div>

</form>

<script>
    // Proste filtrowanie uczniów po klasie
    (function () {
        const classSelect   = document.getElementById('class_id');
        const studentSelect = document.getElementById('student_id');

        const allOptions = Array.from(studentSelect.options);

        function filterStudents() {
            const classId = classSelect.value;

            // Zostaw pierwszą opcję "— wybierz —"
            studentSelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = '— wybierz ucznia —';
            studentSelect.appendChild(placeholder);

            allOptions.forEach(opt => {
                if (!opt.value) return; // pomijamy placeholder z oryginału
                const optClass = opt.getAttribute('data-class');
                if (!classId || optClass === classId) {
                    studentSelect.appendChild(opt);
                }
            });
        }

        classSelect.addEventListener('change', filterStudents);

        // od razu po załadowaniu (np. gdy wracamy z błędem walidacji)
        filterStudents();
    })();
</script>

@endsection
