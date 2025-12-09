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

}
