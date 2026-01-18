<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherPointsController extends Controller
{
    public function create()
    {
        $teacher = Auth::user();

        // tylko nauczyciele / dyrekcja
        if (!$teacher || $teacher->is_teacher == 0) {
            abort(403, 'To zaklęcie jest tylko dla nauczycieli.');
        }

        // Przedmioty, których uczy nauczyciel (users_has_subjects)
        $subjects = $teacher->subjects()->orderBy('name')->get();

        // Wszystkie klasy (4a, 5a, ...)
        $classes = SchoolClass::orderBy('name')->get();

        // Wszyscy uczniowie (filtrowani w JS po klasie)
        $students = User::where('is_teacher', 0)
            ->with(['class', 'house'])
            ->orderBy('class_id')
            ->orderBy('surname')
            ->get();

        return view('teacher.points.create', compact(
            'teacher',
            'subjects',
            'classes',
            'students'
        ));
    }

    public function store(Request $request)
    {
        $teacher = Auth::user();

        if (!$teacher || $teacher->is_teacher == 0) {
            abort(403, 'To zaklęcie jest tylko dla nauczycieli.');
        }

        $data = $request->validate([
            'class_id'   => ['required', 'integer', 'exists:classes,class_id'],
            'student_id' => ['required', 'integer', 'exists:users,user_id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,subject_id'],
            'points'     => ['required', 'integer', 'between:-50,50'],
        ]);

        // Uczeń musi być z wybranej klasy i nie może być nauczycielem
        $student = User::where('user_id', $data['student_id'])
            ->where('class_id', $data['class_id'])
            ->where('is_teacher', 0)
            ->firstOrFail();

        // Nauczyciel musi być przypisany do tego przedmiotu
        if (! $teacher->subjects()->where('subjects.subject_id', $data['subject_id'])->exists()) {
            return back()
                ->withErrors(['subject_id' => 'Nie możesz przyznawać punktów z tego przedmiotu.'])
                ->withInput();
        }

        Point::create([
            'user_id'    => $student->user_id,
            'teacher_id' => $teacher->user_id,
            'subject_id' => $data['subject_id'],
            'points'     => $data['points'],
        ]);

        return redirect()
            ->route('teacher.points.create')
            ->with('status', 'Punkty zostały przyznane 🎉');
    }

    public function history()
    {
        $teacher = Auth::user();

        if (!$teacher || $teacher->is_teacher == 0) {
            abort(403, 'To zaklęcie jest tylko dla nauczycieli.');
        }

        // Pobieramy punkty przyznane przez tego nauczyciela
        $points = \App\Models\Point::with([
                'student.class',
                'student.house',
                'subject',
            ])
            ->where('teacher_id', $teacher->user_id)
            ->orderByDesc('created_at')
            ->paginate(20); // paginacja po 20 wpisów

        return view('teacher.points.history', compact('teacher', 'points'));
    }

    public function createBulk()
{
    $teacher = Auth::user();

    if (!$teacher || $teacher->is_teacher == 0) {
        abort(403, 'To zaklęcie jest tylko dla nauczycieli.');
    }

    $subjects = $teacher->subjects()->orderBy('name')->get();
    $classes  = SchoolClass::orderBy('name')->get();

    // Jeśli filtr klasy jest podany (np. z query stringu ?class_id=1),
    // wczytamy uczniów tej klasy, żeby od razu pokazać tabelę
    $selectedClassId = request()->query('class_id');

    $students = collect();
    if ($selectedClassId) {
        $students = User::where('is_teacher', 0)
            ->where('class_id', $selectedClassId)
            ->with(['class', 'house'])
            ->orderBy('surname')
            ->orderBy('name')
            ->get();
    }

    return view('teacher.points.bulk', compact(
        'teacher',
        'subjects',
        'classes',
        'students',
        'selectedClassId'
    ));
}

public function storeBulk(Request $request)
{
    $teacher = Auth::user();

    if (!$teacher || $teacher->is_teacher == 0) {
        abort(403, 'To zaklęcie jest tylko dla nauczycieli.');
    }

    $data = $request->validate([
        'class_id'   => ['required', 'integer', 'exists:classes,class_id'],
        'subject_id' => ['required', 'integer', 'exists:subjects,subject_id'],
        'points'     => ['array'],               // tablica: student_id => punkty
        'points.*'   => ['nullable', 'integer', 'between:-50,50'],
    ]);

    // sprawdź, czy nauczyciel ma ten przedmiot
    if (! $teacher->subjects()->where('subjects.subject_id', $data['subject_id'])->exists()) {
        return back()
            ->withErrors(['subject_id' => 'Nie możesz przyznawać punktów z tego przedmiotu.'])
            ->withInput();
    }

    $classId = $data['class_id'];
    $pointsByStudent = $data['points'] ?? [];

    $createdCount = 0;

    foreach ($pointsByStudent as $studentId => $pts) {
        if ($pts === null || $pts === '' || (int)$pts === 0) {
            continue; // puste albo 0 – ignorujemy
        }

        $pts = (int) $pts;

        // upewniamy się, że to uczeń z tej klasy
        $student = User::where('user_id', $studentId)
            ->where('class_id', $classId)
            ->where('is_teacher', 0)
            ->first();

        if (! $student) {
            continue;
        }

        Point::create([
            'user_id'    => $student->user_id,
            'teacher_id' => $teacher->user_id,
            'subject_id' => $data['subject_id'],
            'points'     => $pts,
        ]);

        $createdCount++;
    }

    return redirect()
        ->route('teacher.points.bulk.create', ['class_id' => $classId])
        ->with('status', "Dodano zaklęcia punktów: {$createdCount} wpisów.");
}


}
