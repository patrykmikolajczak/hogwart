<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Support\Facades\Auth;

class StudentPointsController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        // tylko uczniowie (is_teacher == 0)
        if (!$student || $student->is_teacher != 0) {
            abort(403, 'To zaklęcie jest tylko dla uczniów.');
        }

        $points = Point::with(['teacher', 'subject'])
            ->where('user_id', $student->user_id)
            ->orderByDesc('created_at')
            ->paginate(20);

        $totalPoints = $points->total()
            ? Point::where('user_id', $student->user_id)->sum('points')
            : 0;

        return view('student.points.index', compact(
            'student',
            'points',
            'totalPoints'
        ));
    }
}
