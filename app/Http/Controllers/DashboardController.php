<?php

namespace App\Http\Controllers;

use App\Services\PointsService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private PointsService $pointsService)
    {
        $this->pointsService = $pointsService;
    }

    public function index()
    {
        $user = Auth::user();

        $housesRanking = $this->pointsService->getHousesRanking();
        $topTeachers   = $this->pointsService->getTopTeachers(5);
        $topClasses   = $this->pointsService->getTopClasses(5);

        $classRanking = null;
        if (!$user->is_teacher && $user->class_id) {
            $classRanking = $this->pointsService->getClassRanking($user->class_id);
        }

        return view('dashboard.index', compact(
            'user',
            'housesRanking',
            'topTeachers',
            'topClasses',
            'classRanking'
        ));
    }
}

