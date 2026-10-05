<?php

namespace App\Http\Controllers;

use App\Http\Requests\StandingsRequest;
use App\Models\Matchday;
use App\Services\StandingsService;
use Illuminate\View\View;

class StandingsController extends Controller
{
    public function index(StandingsRequest $request, StandingsService $service): View
    {
        $selectedMatchday = $request->matchdayNumber();
        $standings = $service->calculate($selectedMatchday);
        $matchdays = Matchday::query()->orderBy('number')->get(['number', 'date']);

        return view('standings.index', compact('standings', 'matchdays', 'selectedMatchday'));
    }
}
