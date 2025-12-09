<?php

namespace App\Services;

use App\Models\House;
use App\Models\Point;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PointsService
{
    // Punkty ucznia (sumarycznie)
    public function getStudentPoints(User $student): int
    {
        return $student->pointsReceived()->sum('points');
    }

    // Ranking uczniów w klasie
    public function getClassRanking(int $classId)
    {
        return DB::table('users as u')
            ->leftJoin('points as p', 'p.user_id', '=', 'u.user_id')
            ->select('u.user_id', 'u.name', 'u.surname', DB::raw('COALESCE(SUM(p.points),0) as total_points'))
            ->where('u.is_teacher', 0)
            ->where('u.class_id', $classId)
            ->groupBy('u.user_id', 'u.name', 'u.surname')
            ->orderByDesc('total_points')
            ->get();
    }

    // Punkty domów
    public function getHousesRanking()
    {
        return DB::table('houses as h')
            ->leftJoin('users as u', 'u.house_id', '=', 'h.house_id')
            ->leftJoin('points as p', 'p.user_id', '=', 'u.user_id')
            ->select('h.house_id', 'h.name', DB::raw('COALESCE(SUM(p.points),0) as total_points'))
            ->groupBy('h.house_id', 'h.name')
            ->orderByDesc('total_points')
            ->get();
    }

    // Top N nauczycieli wg przyznanych punktów
    public function getTopTeachers(int $limit = 10)
    {
        return DB::table('points as p')
            ->leftJoin('users as u', 'p.teacher_id', '=', 'u.user_id')
            ->select('u.user_id', 'u.name', 'u.surname', DB::raw('SUM(p.points) as total_points'))
            ->groupBy('u.user_id', 'u.name', 'u.surname')
            ->orderByDesc('total_points')
            ->limit($limit)
            ->get();
    }
}
