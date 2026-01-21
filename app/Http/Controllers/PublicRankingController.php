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

    public function tournament(PointsService $pointsService)
    {
        $housesRanking = $pointsService->getTournamentRanking();

        return view('public.tournament-ranking', compact('housesRanking'));
    }
}
