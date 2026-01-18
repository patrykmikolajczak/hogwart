<?php

namespace App\Http\Controllers;

use App\Services\PointsService;

class PublicRankingController extends Controller
{
    public function houses(PointsService $pointsService)
    {
        $housesRanking = $pointsService->getHousesRanking();

        return view('public.houses-ranking', compact('housesRanking'));
    }
}
