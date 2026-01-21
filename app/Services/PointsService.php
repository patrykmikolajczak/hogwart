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
        $userPoints = DB::table('users as u')
            ->join('points as p', 'p.user_id', '=', 'u.user_id')
            ->select('u.house_id', DB::raw('SUM(p.points) as user_points'))
            ->groupBy('u.house_id');

        $houseDirectPoints = DB::table('points as p2')
            ->whereNull('p2.user_id')          // punkty nieprzypisane do ucznia
            ->whereNotNull('p2.house_id')      // ale przypisane do domu
            ->select('p2.house_id', DB::raw('SUM(p2.points) as house_points'))
            ->groupBy('p2.house_id');

        $ranking = DB::table('houses as h')
            ->leftJoinSub($userPoints, 'up', function ($join) {
                $join->on('up.house_id', '=', 'h.house_id');
            })
            ->leftJoinSub($houseDirectPoints, 'hp', function ($join) {
                $join->on('hp.house_id', '=', 'h.house_id');
            })
            ->select(
                'h.house_id',
                'h.name',
                DB::raw('COALESCE(up.user_points,0) + COALESCE(hp.house_points,0) as total_points')
            )
            ->orderByDesc('total_points')
            ->get();

        return $ranking;
        // return DB::table('houses as h')
        //     ->leftJoin('users as u', 'u.house_id', '=', 'h.house_id')
        //     ->leftJoin('points as p', 'p.user_id', '=', 'u.user_id')
        //     ->leftJoin('points as p2', 'p2.house_id', '=', 'h.house_id')
        //     ->select('h.house_id', 'h.name', DB::raw('COALESCE(SUM(p.points),0) as total_points'))
        //     ->groupBy('h.house_id', 'h.name')
        //     ->orderByDesc('total_points')
        //     ->get();
    }

    public function getTournamentRanking()
    {

        $ranking = DB::table('points as p')
            ->leftJoin('houses as h', 'p.house_id', '=', 'h.house_id')
            ->whereNull('p.user_id')          // punkty nieprzypisane do ucznia
            ->whereNotNull('p.house_id')      // ale przypisane do domu
            ->whereIn('p.point_category_id',[15,16,17,18])
            ->select('h.house_id', 'h.name', DB::raw('SUM(p.points) as total_points'))
            ->groupBy('h.house_id', 'h.name')
            ->get();

        return $ranking;
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

    public function getTopClasses(int $limit = 10)
    {
        return DB::table('points as p')
            ->leftJoin('users as u', 'p.user_id', '=', 'u.user_id')
            ->leftJoin('classes as c', 'u.class_id', '=', 'c.class_id')
            ->select('c.class_id', 'c.name', DB::raw('SUM(p.points) as total_points'))
            ->groupBy('c.class_id', 'c.name')
            ->orderByDesc('total_points')
            ->limit($limit)
            ->get();
    }
}
