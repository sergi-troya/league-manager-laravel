<?php

namespace App\Http\Controllers;

use App\Models\Scorer;
use App\Services\StandingsService;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(StandingsService $service): View
    {
        $snapshot = $service->seasonSnapshot();
        $standings = $snapshot['standings'];
        $data = Arr::only($snapshot, [
            'matchday_count', 'games_completed', 'games_pending', 'total_goals',
        ]) + ['url' => route('matchday.index')];
        $teams_data = [
            'teams_count' => $standings->count(),
            'top_teams' => $standings->take(3)->map(static fn (array $row) => [
                'value' => $row['points'],
                'label' => $row['team']->short_name ?: $row['team']->full_name ?: $row['team']->code,
            ])->values()->all(),
            'url' => route('standings.index'),
        ];
        $scorers_data = Scorer::dashboard();

        return view('home.index', compact('data', 'teams_data', 'scorers_data'));
    }
}
