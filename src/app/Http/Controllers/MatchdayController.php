<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matchday;
use App\Models\Game;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MatchdayController extends Controller
{
    public function index( Request $request) : View {
        $matchdayData = Matchday::with('games')->get();
        $matchday_number = $request->input('matchday') ?? 1;
        return view('matchday.index', compact('matchdayData', 'matchday_number'));
    }

    public function editGame(Request $request, Matchday $matchday, Game $game) : View {
        abort_unless((int) $game->matchday_id === (int) $matchday->id, 404);
        $game->load(['homeTeam', 'awayTeam']);
        return view('game.edit', compact('matchday', 'game'));
    }

    public function updateGame(Request $request, Matchday $matchday, Game $game): RedirectResponse {
        abort_unless((int) $game->matchday_id === (int) $matchday->id, 404);

        $validatedData = $request->validate([
            'home_goals' => 'required|integer|min:0|max:2147483647',
            'away_goals' => 'required|integer|min:0|max:2147483647',
            'home_team_id' => 'prohibited',
            'away_team_id' => 'prohibited',
            'matchday_id' => 'prohibited',
        ]);

        $game->update([
            'home_goals' => $validatedData['home_goals'],
            'away_goals' => $validatedData['away_goals'],
        ]);

        return redirect()
            ->route('matchday.index', ['matchday' => $matchday->id])
            ->with('success', 'Game updated successfully.');
    }
}
