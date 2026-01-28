<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\PointCategory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StatisticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('statistics.index', compact(
            'user'
        ));
    }

    public function classDaily()
    {
        $teacher = Auth::user();

        if (!$teacher || (int)$teacher->is_teacher === 0) {
            abort(403, 'Tylko dla nauczycieli.');
        }

        // Lista klas do selecta
        $classes = SchoolClass::orderBy('name')->get();

        $classId = request()->query('class_id');
        $daysParam = (int) request()->query('days', 14);
        $daysParam = in_array($daysParam, [7, 14, 30], true) ? $daysParam : 14;

        // Zakres dat: ostatnie N dni (włącznie z dziś)
        $to = now()->endOfDay();
        $from = now()->subDays($daysParam - 1)->startOfDay();

        // Kolumny (dni)
        $days = collect(CarbonPeriod::create($from->copy()->startOfDay(), $to->copy()->startOfDay()))
            ->map(fn($d) => $d->format('Y-m-d'));

        $students = collect();
        $pivot = [];
        $totals = [];

        if ($classId) {
            // Uczniowie klasy
            $students = User::where('is_teacher', 0)
                ->with('house')
                ->where('class_id', $classId)
                ->orderBy('surname')
                ->orderBy('name')
                ->get(['user_id', 'name', 'surname', 'house_id']);

            // Agregaty punktów per uczeń per dzień
            $rows = DB::table('points as p')
                ->join('users as u', 'u.user_id', '=', 'p.user_id')
                ->where('u.class_id', $classId)
                ->whereBetween('p.created_at', [$from, $to])
                ->selectRaw('p.user_id, DATE(p.created_at) as day')
                ->selectRaw('SUM(p.points) as total')
                ->selectRaw('SUM(CASE WHEN p.points > 0 THEN p.points ELSE 0 END) as plus')
                ->selectRaw('SUM(CASE WHEN p.points < 0 THEN p.points ELSE 0 END) as minus')
                ->groupBy('p.user_id', DB::raw('DATE(p.created_at)'))
                ->get();

            // Pivot [user_id][day] => total/plus/minus
            foreach ($rows as $r) {
                $pivot[$r->user_id][$r->day] = [
                    'total' => (int) $r->total,
                    'plus'  => (int) $r->plus,
                    'minus' => (int) $r->minus,
                ];
            }

            // Sumy okresu per uczeń
            foreach ($students as $s) {
                $sumT = $sumP = $sumM = 0;
                foreach ($days as $day) {
                    $cell = $pivot[$s->user_id][$day] ?? ['total'=>0,'plus'=>0,'minus'=>0];
                    $sumT += $cell['total'];
                    $sumP += $cell['plus'];
                    $sumM += $cell['minus'];
                }
                $totals[$s->user_id] = ['total'=>$sumT,'plus'=>$sumP,'minus'=>$sumM];
            }
        }

        return view('statistics.class_daily', compact(
            'teacher',
            'classes',
            'classId',
            'daysParam',
            'days',
            'students',
            'pivot',
            'totals'
        ));
    }

    public function classCategories()
    {
        $teacher = Auth::user();

        if (!$teacher || (int)$teacher->is_teacher === 0) {
            abort(403, 'Tylko dla nauczycieli.');
        }

        $classes = SchoolClass::orderBy('name')->get();
        $categories = PointCategory::orderBy('name')
            ->where('tournament',0)
            ->get();

        $classId = request()->query('class_id');

        $students = collect();
        $pivot = [];
        $rowTotals = [];
        $colTotals = [];

        if ($classId) {
            $students = User::where('is_teacher', 0)
                ->where('class_id', $classId)
                ->with('house')
                ->orderBy('surname')->orderBy('name')
                ->get(['user_id','name','surname', 'house_id']);

            // Agregaty: uczeń + kategoria
            $rows = DB::table('points as p')
                ->join('users as u', 'u.user_id', '=', 'p.user_id')
                ->where('u.class_id', $classId)
                ->selectRaw('p.user_id, p.point_category_id')
                ->selectRaw('SUM(p.points) as total')
                ->selectRaw('SUM(CASE WHEN p.points > 0 THEN p.points ELSE 0 END) as plus')
                ->selectRaw('SUM(CASE WHEN p.points < 0 THEN p.points ELSE 0 END) as minus')
                ->groupBy('p.user_id', 'p.point_category_id')
                ->get();

            // Pivot [user_id][category_id]
            foreach ($rows as $r) {
                $pivot[$r->user_id][$r->point_category_id] = [
                    'total' => (int)$r->total,
                    'plus'  => (int)$r->plus,
                    'minus' => (int)$r->minus,
                ];
            }

            // Sumy per uczeń (wiersz)
            foreach ($students as $s) {
                $t = $p = $m = 0;
                foreach ($categories as $cat) {
                    $cell = $pivot[$s->user_id][$cat->point_category_id] ?? ['total'=>0,'plus'=>0,'minus'=>0];
                    $t += $cell['total'];
                    $p += $cell['plus'];
                    $m += $cell['minus'];
                }
                $rowTotals[$s->user_id] = ['total'=>$t,'plus'=>$p,'minus'=>$m];
            }

            // Sumy per kategoria (kolumna)
            foreach ($categories as $cat) {
                $t = $p = $m = 0;
                foreach ($students as $s) {
                    $cell = $pivot[$s->user_id][$cat->point_category_id] ?? ['total'=>0,'plus'=>0,'minus'=>0];
                    $t += $cell['total'];
                    $p += $cell['plus'];
                    $m += $cell['minus'];
                }
                $colTotals[$cat->point_category_id] = ['total'=>$t,'plus'=>$p,'minus'=>$m];
            }
        }

        return view('statistics.class_categories', compact(
            'teacher',
            'classes',
            'categories',
            'classId',
            'students',
            'pivot',
            'rowTotals',
            'colTotals'
        ));
    }
}


