<?php

namespace App\Http\Controllers;

use App\Services\PointsService;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function __construct(private PointsService $pointsService)
    {
        $this->pointsService = $pointsService;
    }

    public function index()
    {
        $user = Auth::user();

        return view('statistics.index', compact(
            'user'
        ));
    }

    public function store()
    {
        $user = Auth::user();

        return view('statistics.index', compact(
            'user'
        ));
    }
}


